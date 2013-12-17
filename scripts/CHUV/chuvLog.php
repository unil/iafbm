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
                return $this->toStringCreate($mode);
                break;
            case "S":
                return $this->toStringDelete($mode);
                break;
            case "U":
                return $this->toStringUpdate($mode);
                break;
        }
    }
    
    public function toStringDelete($mode){
        $ret = "----------------\n";
        $ret .= "$this->_modifTypeLibelle \n\n";
        $ret .= "Nom du service:\t\t\t $this->_serviceName\n";
        $ret .= "Abréviation du service: \t $this->_serviceAbreviation\n";
        $ret .= "Commentaire: \t\t\t\t $this->_statusComment\n";
        if($this->_chuvComment){
            $ret .= "Commentaire du CHUV : \t\t\t $this->_chuvComment\n";
        }
        
        if($this->_rattachementRelations){
            $ret .= "\nPersonnes dont le rattachement doit être changé avant suppression:\n";
            foreach($this->_rattachementRelations as $p){
                $ret .= "\t\t\t- ".$p['personne_prenom']." ".$p['personne_nom']." (".$p['activite_nom_nom'].") -> https://wwwfbm.unil.ch/iafbm/personnes/".$p['personne_id']."\n";
            }
            $ret .= "Veuillez svp informer l'administrateur de l'iafbm lorsque vous aurez rattaché ces personnes à un nouveau service. Merci\n";
        }
        
        if($this->_status){
            if($mode == 'admin'){
                $ret .= "Modif ID: \t\t\t\t $this->_modifId\n";
                $ret .= "Table: \t\t\t\t\t Rattachements\n";
                $ret .= "id: \t\t\t\t\t ".$this->_transaction->results[0]['xparams']['id']."\n";
                if($this->_exception){
                    $ret .= "Exception: \t\t ".$this->_exception->__toString()."\n";
                }
            }
        }else{
            if($mode == 'user'){
                return null;
            }elseif($mode = 'admin'){
                $ret .= "Modif ID: \t\t\t\t $this->_modifId\n";
                $ret .= "id_chuv: \t\t\t\t $this->_serviceAbreviation\n";
                $ret .= "Table: \t\t\t\t\t Rattachements\n";
                if($this->_exception){
                    $ret .= "Exception: \t\t ".$this->_exception->__toString()."\n";
                }
            }
        }
        
        $ret .= "----------------\n";
        return $ret;
    }
    
    public function toStringUpdate($mode){
        $ret = "----------------\n";
        $ret .= "$this->_modifTypeLibelle \n\n";
        $ret .= "Nouveau nom du service:\t\t\t $this->_serviceName\n";
        $ret .= "Ancien nom du service: \t\t\t $this->_beforeModifServiceName\n";
        $ret .= "Nouvelle abréviation du service: \t $this->_serviceAbreviation\n";
        $ret .= "Ancienne abréviation du service: \t $this->_beforeModifServiceAbreviation\n";
        $ret .= "Commentaire: \t\t\t\t $this->_statusComment\n";
        if($this->_chuvComment){
            $ret .= "Commentaire du CHUV : \t\t\t $this->_chuvComment\n";
        }
        
        if($this->_status){
            if($mode == 'admin'){
                $ret .= "Modif ID: \t\t\t\t $this->_modifId\n";
                $ret .= "Table: \t\t\t\t\t Rattachements\n";
                $ret .= "id: \t\t\t\t\t ".$this->_transaction->results[0]['xparams']['id']."\n";
                if($this->_exception){
                    $ret .= "Exception: \t\t ".$this->_exception->__toString()."\n";
                }
            }
        }else{
            if($mode == 'user'){
                return null;
            }elseif($mode = 'admin'){
                $ret .= "Modif ID: \t\t\t\t $this->_modifId\n";
                $ret .= "id_chuv: \t\t\t\t $this->_serviceAbreviation\n";
                $ret .= "Table: \t\t\t\t\t Rattachements\n";
                if($this->_exception){
                    $ret .= "Exception: \t\t ".$this->_exception->__toString()."\n";
                }
            }
        }
        
        $ret .= "----------------\n";
        return $ret;
    }
    
    public function toStringCreate($mode){
        $ret = "----------------\n";
        $ret .= "$this->_modifTypeLibelle \n\n";
        $ret .= "Nom du service: \t $this->_serviceName\n";
        $ret .= "Abréviation: \t\t $this->_serviceAbreviation\n";
        $ret .= "Responsable: \t\t $this->_serviceResponsable\n";
        if($this->_chuvComment){
            $ret .= "Commentaire du CHUV : \t $this->_chuvComment\n";
        }
        $ret .= "Commentaire: \t\t $this->_statusComment\n";
        
        if($this->_status){
            if($mode == 'admin'){
                $ret .= "Modif ID: \t\t $this->_modifId\n";
                $ret .= "id_chuv: \t\t $this->_serviceAbreviation\n";
                $ret .= "Table: \t\t\t Rattachements\n";
                $ret .= "id: \t\t\t ".$this->_transaction->last_insert_id."\n";
                if($this->_exception){
                    $ret .= "Exception: \t\t ".$this->_exception->__toString()."\n";
                }
            }
        }else{
            if($mode == 'user'){
                return null;
            }elseif($mode = 'admin'){
                $ret .= "Modif ID: \t\t $this->_modifId\n";
                $ret .= "id_chuv: \t\t $this->_serviceAbreviation\n";
                $ret .= "Table: \t\t\t Rattachements\n";
                if($this->_exception){
                    $ret .= "Exception: \t\t ".$this->_exception->__toString()."\n";
                }
            }
        }
        
        $ret .= "----------------\n";
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