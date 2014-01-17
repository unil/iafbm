<?php

require_once(dirname(__file__).'/../Script.php');
require_once(dirname(__file__).'/chuvLog.php');

/**
 * @package scripts-migration
 */
class chuvStructImport extends iafbmScript {
    
    private $deltaDirectory = "./deltaStruct/";
    private $_releveEmailAddr = "mathieu.noverraz@unil.ch";
    private $_iafbmAdminEmailAddr = "mathieu.noverraz@unil.ch";
    
    function run() {
        //Get all modifications in XML files
        $modifs = $this->getUbModifications();
        //Remove modifications already executed
        //$modifs = $this->filterModifications($modifs, $this->getIgnoredModifs());
        //Processing modifications and return a log
        $logs = $this->processModifs($modifs);
        //Send a feedback email to the Administrator and the "Relève"
        $this->sendEmail($this->_releveEmailAddr, "[iafbm][user] - Changement de la structure CHUV appliquée à l'iafbm",$this->makeMessage($logs, 'user'));
        $this->sendEmail($this->_iafbmAdminEmailAddr, "[iafbm][admin] - Changement de la structure CHUV appliquée à l'iafbm",$this->makeMessage($logs, 'admin'));
    }
    
    /*
     * Make and format the message to send to users
     * @param ChuvLog $log Log of the modification.
     * @oaram string $mode Mode of log (user,admin).
     * @return string The message to send.
     */
    private function makeMessage($logs, $mode){
        switch($mode){
            case 'admin';
                $head = "Lire l'article concernant le comportement du script d'import de la structure CHUV avant la lecture de cet email. https://wwwfbm.unil.ch/wiki/iafbm/iafbm:documentation:script_de_modification_de_la_structure_organisationnelle_du_chuv\n\n\n";
                break;
            case 'user':
                $head = "Les modifications suivantes ont été appliquées à l'iafbm:\n\n\n";
                break;
        }
        $content = "Début des modifications\n\n";
        foreach($logs as $log){
            $content .= $log->toString($mode);
        }
        $content .= "\nFin des modifications.";
        return $head.$content;
    }
    
    /*
     * Get ignored modifications from database
     * @return array Ignored modifications
     */
    private function getIgnoredModifs(){
        $model = xModel::load('script_chuv')->get();
        $modifsToIgnore = array();
        foreach($model as $modif){
            $modifsToIgnore[] = $modif['modif_id'];
        }
        return $modifsToIgnore;
    }
    
    /*
     * Ignore modification for the next execution of the script
     * the function write in database the modif id to ignore
     * @param ChuvLog $log Log of the modification
     * @return bool Returns true if inserted in table and false otherwise; 
     */
    private function ignoreModif($log){       
        if(xModel::load('script_chuv', array('modif_id'=>$log->modifId))->get()){ //exists already in table
            echo "La modification ".$log->modifId." existe déjà en table et a été jouée une nouvelle fois. Il faut contrôler.\n";
        }else{
            $model = xModel::load('script_chuv',array(
                    'modif_id' => $log->modifId,
                    'operation' => $log->modifType,
                    'log' => $log->toString('admin'),
                    'date' => date("Y-m-d"),
            ))->put();
            if(@$model[results][0]['result']['xsuccess'])
                return true;
        }
        return false;
    }

    /*
     * Get persons who is active in the service
     * @param int $id_chuv Id for the service like (CCV, PMU)
     * @return array Array of persons 
     */
    private function personsInService($id_chuv){
        return xModel::load('personne_activite', array(
            'rattachement_id_chuv' => $id_chuv,
            'actif' => 1
        ))->get();
    }
    
    /*
     * Update a CHUV service naming
     * @param DOMElement $modif A modification
     * @return ChuvLog Log of the operation
     */
    public function deleteService($modif){
        //modifications values
        $id_chuv = $modif->getElementsByTagName('entityCode')->item(0)->nodeValue;
        $nom = $modif->getElementsByTagName('longLabel')->item(0)->nodeValue;
        $abreviation = $id_chuv;
        $chuvComment = (@$modif->getElementsByTagName('entityComments')) ? @$modif->getElementsByTagName('entityComments')->item(0)->nodeValue : null;
        $modifId = $modif->getElementsByTagName('modifId')->item(0)->nodeValue;
        //log
        $log = new ChuvLog($modifId);
        $log->modifTypeLibelle = 'Suppression de service';
        $log->modifType = 'S';
        $log->id_chuv = $id_chuv;
        $log->serviceName = $nom;
        $log->serviceAbreviation = $abreviation;
        $log->chuvComment = $chuvComment;
        
        $exists = $this->rowExists('rattachement', 'id_chuv', $id_chuv);
        $personsInService = $this->personsInService($id_chuv);
        $log->rattachementRelations = $personsInService;
        
        if($exists){
            if(!$personsInService){
                try{
                    $t = new xTransaction();
                    $t->start();
                        $model = xModel::load('rattachement', array(
                                'id' => $exists,
                                'actif' => 0,
                        ));
                        $t->execute($model, 'post');
                    $t->end();
                    if($t->results[0]['result']['xsuccess']){
                        $log->status = true;
                        $log->statusComment = "Le service a correctement été supprimé dans l'iafbm";
                    }else{
                        $log->status = false;
                        $log->modifTypeLibelle = 'Suppression de service';
                        $log->statusComment = "Un problème a été détecté";
                    }
                    $log->transaction = $t;
                }catch(xException $e){
                    $log->exception = $e;
                    return $log;
                }
            }else{
                $log->status = false;
                $log->modifTypeLibelle = 'Suppression de service';
                $log->statusComment = "Des personnes sont encore rattachées au service, il est donc impossible de supprimer le service.";
            }
        }else{
            $log->status = false;
            $log->modifTypeLibelle = 'Suppression de service';
            $log->statusComment = "Le rattachement '".$nom."' n'existait pas et n'a donc pas pu être supprimé";
        }
        return $log;
    }
    
    /*
     * Update a CHUV service naming
     * @param DOMElement $modif A modification
     * @return ChuvLog Log of the operation
     */
    public function updateService($modif){
        //modifications values
        $id_chuv = $modif->getElementsByTagName('entityCode')->item(0)->nodeValue;
        $nom = $modif->getElementsByTagName('longLabel')->item(0)->nodeValue;
        $abreviation = $id_chuv;
        $chuvComment = (@$modif->getElementsByTagName('entityComments')) ? @$modif->getElementsByTagName('entityComments')->item(0)->nodeValue : null;
        $modifId = $modif->getElementsByTagName('modifId')->item(0)->nodeValue;
        //log
        $log = new ChuvLog($modifId);
        $log->modifTypeLibelle = 'Changement de libellé de service';
        $log->modifType = 'U';
        $log->id_chuv = $id_chuv;
        $log->serviceName = $nom;
        $log->serviceAbreviation = $abreviation;
        $log->chuvComment = $chuvComment;
        
        $exists = $this->rowExists('rattachement', 'id_chuv', $id_chuv);
        
        if($exists){
            $rowBeforeModif =  xModel::load('rattachement', array('id' => $exists))->get();
            $log->beforeModifServiceName = $rowBeforeModif[0]['nom'];
            $log->beforeModifServiceAbreviation = $rowBeforeModif[0]['abreviation'];
            try{
                $t = new xTransaction();
                $t->start();
                    $model = xModel::load('rattachement', array(
                            'id' => $exists,
                            'nom' => $nom,
                            'abreviation' => $abreviation
                    ));
                    $t->execute($model, 'post');
                $t->end();
                if($t->results[0]['result']['xsuccess']){
                    $log->status = true;
                    $log->statusComment = "Le libellé du service a correctement été modifié dans l'iafbm";
                }else{
                    $log->status = false;
                    $log->modifTypeLibelle = 'Changement de libellé de service';
                    $log->statusComment = "Un problème a été détecté";
                }
                $log->transaction = $t;
            }catch(xException $e){
                return $log;
            }
        }else{
            $log->status = false;
            $log->modifTypeLibelle = 'Changement de libellé de service';
            $log->statusComment = "Le rattachement '".$nom."' n'existait pas et n'a donc pas pu être mis à jour";
        }
        return $log;
    }
    
    
    /*
     * Create a CHUV struct service
     * @param DOMElement $modif A modification
     * @return ChuvLog Log of the operation
     */
    public function createService($modif){
        //modifications values
        $id_chuv = $modif->getElementsByTagName('entityCode')->item(0)->nodeValue;
        $nom = $modif->getElementsByTagName('longLabel')->item(0)->nodeValue;
        $responsable = $modif->getElementsByTagName('respTitle')->item(0)->nodeValue." ".$modif->getElementsByTagName('entityResp')->item(0)->nodeValue." (".$modif->getElementsByTagName('respFunct')->item(0)->nodeValue.")";
        $abreviation = $id_chuv;
        $chuvComment = (@$modif->getElementsByTagName('entityComments')) ? @$modif->getElementsByTagName('entityComments')->item(0)->nodeValue : null;
        $modifId = $modif->getElementsByTagName('modifId')->item(0)->nodeValue;
        //log
        $log = new ChuvLog($modifId);
        $log->modifTypeLibelle = 'Création de service';
        $log->modifType = 'C';
        $log->id_chuv = $id_chuv;
        $log->serviceName = $nom;
        $log->serviceAbreviation = $abreviation;
        $log->serviceResponsable = $responsable;
        $log->chuvComment = $chuvComment;
        
        if(!$this->rowExists('rattachement', 'id_chuv', $id_chuv)){
            try{
                $t = new xTransaction();
                $t->start();
                    $model = xModel::load('rattachement', array(
                            'id_unil' => null,
                            'id_chuv' => $id_chuv,
                            'actif' => 1,
                            'section_id' => 1,
                            'nom' => $nom,
                            'abreviation' => $abreviation
                    ));
                    $t->execute($model, 'put');
                $t->end();
                
                if($t->results[0]['result']['xsuccess']){
                    $log->status = true;
                    $log->statusComment = "Le service a correctement été créé dans l'iafbm";
                }else{
                    $log->status = false;
                    $log->modifTypeLibelle = 'Création de service';
                    $log->statusComment = "Un problème a été détecté";
                }
                $log->transaction = $t;
            }catch(xException $e){
                $log->status = false;
                $log->modifTypeLibelle = 'Création de service';
                $log->statusComment = "Un problème a été détecté";
                $log->exception = $e;
                return $log;
            }
        }else{
            $log->status = false;
            $log->modifTypeLibelle = 'Création de service';
            $log->statusComment = "Le rattachement existait déjà";
        }
        return $log;
    }
    
    /*
     * Send an email
     * @param string $to
     * @param string $subject
     * @param string $message
     * @return bool Returns true if send, false otherwise.
     */
    function sendEmail($to,$subject, $message){
        $headers   = array();
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-type: text/plain; charset=UTF-8";
        $headers[] = "From: ".xContext::$config->site->mail->noreply->name."<".xContext::$config->site->mail->noreply->mail.">";
        $headers[] = "Reply-To: ".xContext::$config->site->mail->noreply->name."<".xContext::$config->site->mail->noreply->mail.">";;
        $headers[] = "Subject: {$subject}";
        $headers[] = "X-Mailer: PHP/".phpversion();
        
        $accepted = mail($to, $subject, $message, implode("\r\n", $headers));
        if($accepted){
            echo "The email has been send\n";
            return true;
        }else{
            echo "The email hasn't been send. An error occured.\n";
            return false;
        }
    }
    
    /*
     * Check if the row in db exists
     * @param string $model
     * @param string $fieldName
     * @param string $fieldValue
     * @return bool|int Returns false if no entry and id int if exists
     */
    function rowExists($model, $fieldName, $fieldValue){
        $rows = xModel::load($model, array(
            $fieldName => $fieldValue
        ))->get();
        if(!!$rows)
            return (int)$rows[0]['id'];
        return false;
    }
    
    /*
     * Process modifications. Dispatch the modifications to the correct action
     * @param array $modifs Array of modifications (DOMElement)
     */
    function processModifs($modifs){
        $log = array();
        foreach($modifs as $modif){
            $modifType = $this->getModificationType($modif);
            switch($modifType){
                case 'C':
                    $l = $this->createService($modif);
                    $log[] = $l;
                    $this->ignoreModif($l);
                    break;
                case 'S':
                    $l = $this->deleteService($modif);
                    $log[] = $l;
                    if($l->status){
                        $this->ignoreModif($l);
                    }elseif (!$this->personsInService($l->id_chuv)) {
                        $this->ignoreModif($l);
                    }
                    break;
                case 'U':
                    $l = $this->updateService($modif);
                    $log[] = $l;
                    $this->ignoreModif($l);
                    break;
            }
        }
        return $log;
    }
    
    /* 
     * Returns modifications which are not in array $modifsToAvoid
     * @param array $modifs Modifications
     * @param array $modifsToAvoid Modifications id to avoid (int)
     * @return array Returns an array of DOMElement
     */
    function filterModifications($modifs, $modifsToAvoid){
        $ret = array();
        foreach($modifs as $modif){
            $modifId = $modif->getElementsByTagName('modifId')->item(0)->nodeValue;
            if(!in_array($modifId, $modifsToAvoid))
                $ret[] = $modif;   
        }
        return $ret;
    }
    
    /* 
     * Get all CHUV modifications for UB's from files given by CHUV
     * @return array Returns an array of DOMElement
     */
    function getUbModifications(){
        $allModifs = array();
        foreach($this->listDirectory($this->deltaDirectory) as $file){
            $xml = $this->importXml($file);
            $modif = $this->getSpecialFlag(array('C','S','U'),$this->getUbModificationsFromXml($xml));
            
            //Array merge to have a whole array.
            if($modif)
            $allModifs = array_merge($allModifs, $modif);
        }
        return $allModifs;
    }
    
    /*
     * Import an XML file to
     * @param string $file File path to the file
     * @return DOMDocument XML document
     */
    function importXml($file) {
        $doc = new DOMDocument();
        //File opening
        if(file_exists($file)){
            return @$doc::load($file);
        } else {
            exit('Echec lors de l\'ouverture du fichier');
        }    
    }
    
    /*
     * Get the modification type
     *      - C -> Creation of an entity
     *      - S -> Deletation of an entity
     *      - U -> Modification of the label of the entity
     * @param DOMElement
     * @return string Returns the type of the modification (C - S - U) or null if the modification is not intressting in the script
     */
    function getModificationType($modif){
        $modifTypeCode = $modif->getElementsByTagName('modifTypeCode')->item(0)->nodeValue;
        $return = $modifTypeCode;
        
        if($modifTypeCode == "M")
            $return = ($modif->getElementsByTagName('modifFlagL')->item(0)->nodeValue == 1) ? "U" : null;
            
        return $return;
    }
    
    /*
     * Get all UB's (unités de bases) modifications from xml flux
     * @param DOMDocument $xml XML document
     * @return array Returns an array of DOMElement
     */
    function getUbModificationsFromXml($xml){
        $ubModifs = array();
        
        $modifs = $xml->documentElement->getElementsByTagName('modif');
        
        foreach($modifs as $modif){
            $levelCode = $modif->getElementsByTagName('levelCode')->item(0)->nodeValue;
            if($levelCode == "UB") $ubModifs[] = $modif;
        }
        return $ubModifs;
    }
    
    /*
     * Get only specials modification flags
     * @param array $flagList An array of allowed flags
     * @param array $modifs An array of UB's modifications (DOMElement)
     * @return array Returns an array of DOMElement
     */
    function getSpecialFlag($flagList, $modifs){
        $r= array();
        
        foreach($modifs as $modif){
            $addElt = false;
            if(in_array($this->getModificationType($modif), $flagList))
                $addElt = true;
            if($addElt == true) $r[] = $modif;
        }
        return $r;
    }
    
    /*
     * Print the modification put in params
     * @param array $elementsArray An array of elements. A unique modification (DOMElements)
     * @return string Value to print
     */
    function printModifs($elementsArray){
        $echo = "";
        foreach($elementsArray as $element){
            $echo .= "===============\nModification type => ".$this->getModificationType($element)."\n===============\n";
            foreach($element->childNodes as $elt){
                if($elt->nodeType != 3){
                    $echo .= $elt->nodeName." => ".$elt->nodeValue."\n";
                }
            }
            $echo .= "\n\n\n\n";
        }
        return $echo;
    }
    
    /*
     * Get the files paths of a directory
     * @param string $directory directory path to list
     * @return array Returns the files paths of the directory
     */
    function listDirectory($directory){
        $files = array();
        if ($handle = opendir($directory)) {      
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..")
                    $files[] = $directory.$entry;
            }        
            closedir($handle);
        }
        return $files;
    }
}

new chuvStructImport();