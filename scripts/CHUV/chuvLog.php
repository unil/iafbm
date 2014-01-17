<?php

class ChuvLog {
    
    private $_modifId;
    private $_modifType;
    private $_modifTypeLibelle;
    private $_id_chuv;
    private $_serviceName;
    private $_beforeModifServiceName;
    private $_serviceAbreviation;
    private $_beforeModifServiceAbreviation;
    private $_serviceResponsable;
    private $_chuvComment;
    private $_status;
    private $_statusComment;
    private $_transaction;
    private $_exception;
    private $_rattachementRelations;
    
    function __construct($modifId) {
        //no controls
        $this->_modifId = $modifId;
    }
    
    public function toString($mode){
        switch($this->_modifType){
            case "C":
                return $this->makeMessage($this->toStringCreate($mode),$mode);
                break;
            case "S":
                return $this->makeMessage($this->toStringDelete($mode),$mode);
                break;
            case "U":
                return $this->makeMessage($this->toStringUpdate($mode),$mode);
                break;
        }
    }
    
    public function makeMessage($specificMessage, $mode){
        //returns only successfull operations to basics users
        //if(!$this->_status && $mode == 'user' && $this->_modifType != 'S') return null;
        if($mode == 'user'){
            if(!$this->_status && $this->_rattachementRelations){
                //Display the error to the user. Because he need to modify record
                //(people in a service which is planned to be deleted)
            }else{
                //Other error are not display to the user.
                return null;
            }
        }

        $ret = "--------------------------------------------------------------------------------------------------------------------------------\n";
        $ret .= "Action: \t\t\t\t\t\t\t $this->_modifTypeLibelle \n";
        
        $ret .= "Etat: \t\t\t\t\t\t\t ";
        if($this->_status){
            $ret .= "OK\n";
        }else{
            $ret .= "Erreur\n";
            $ret .= "Message: \t\t\t\t\t\t ".$this->_statusComment."\n";
        }
        $ret .= "\n";
        
        $ret .= "Nom du service: \t\t\t\t\t $this->_serviceName\n";
        $ret .= "Abréviation du service: \t\t\t $this->_serviceAbreviation\n";
        if($this->_chuvComment){
            $ret .= "Commentaire du CHUV : \t\t\t $this->_chuvComment\n";
        }
        
        $ret .= $specificMessage;
        
        if($mode == 'admin'){
            $ret .= "\n";
            $ret .= "Modif ID: \t\t\t\t\t\t $this->_modifId\n";
            $ret .= "id_chuv: \t\t\t\t\t\t $this->_serviceAbreviation\n";
            $ret .= "Table: \t\t\t\t\t\t\t Rattachements\n";
            if(@$this->_transaction->last_insert_id){
                $ret .= "id: \t\t\t\t\t\t\t\t ".$this->_transaction->last_insert_id."\n";
            }
            if(@$this->_transaction->results[0]['xparams']['id']){
                $ret .= "id: \t\t\t\t\t\t\t\t ".$this->_transaction->results[0]['xparams']['id']."\n"; 
            }
            if($this->_exception){
                $ret .= "Exception: \t\t\t\t\t\t ".$this->_exception->__toString()."\n";
            }
        }
        
        $ret .= "--------------------------------------------------------------------------------------------------------------------------------\n";
        return $ret;
    }
    
    public function toStringDelete($mode){
        
        $ret = "";
        if($this->_rattachementRelations){
            $ret = "\nAction relève:\t\t\t\t\tPersonnes dont le rattachement doit être changé avant suppression:\n";
            foreach($this->_rattachementRelations as $p){
                $ret .= "\t\t\t\t\t\t\t\t\t\t\t- ".$p['personne_prenom']." ".$p['personne_nom']." (".$p['activite_nom_nom'].") -> https://wwwfbm.unil.ch/iafbm/personnes/".$p['personne_id']."\n";
            }
            $ret .= "\t\t\t\t\t\t\t\tVeuillez svp informer l'administrateur de l'iafbm lorsque vous aurez rattaché ces personnes à un nouveau service. Merci\n";
        }
        
        
        return $ret;
    }
    
    public function toStringUpdate($mode){
        $ret = "Ancien nom du service: \t\t\t $this->_beforeModifServiceName\n";
        $ret .= "Ancienne abréviation du service: \t $this->_beforeModifServiceAbreviation\n";
        
        return $ret;
    }
    
    public function toStringCreate($mode){
        $ret = "";
        $ret .= "Responsable: \t\t\t\t\t $this->_serviceResponsable\n";
        
        return $ret;
    }
    
    
    
    
    //-------------------------------------------------------------------------
    public function __get($var)
    {
        $func = 'get'.ucfirst($var);
        $property = '_'.$var;
        if (method_exists($this, $func))
            return $this->$func();
        elseif(property_exists($this,$property))
            return $this->$property;
        else
            throw new InexistentPropertyException("Inexistent property: $var");
    }

    public function __set($var, $value)
    {
        $func = 'set'.ucfirst($var);
        $property = '_'.$var;
        if (method_exists($this, $func))
        {
            $this->$func($value);
        }elseif(property_exists($this, $property)){
            $this->$property = $value;
        }else {
            throw new InexistentPropertyException("Inexistent property: $var");
        }
    }
}
?>