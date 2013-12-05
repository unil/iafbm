<?php

require_once(dirname(__file__).'/../Script.php');

/**
 * @package scripts-migration
 */
class iafbmIssue246 extends iafbmScript {

    function run() {
        // Single run test
        if ($this->already_run()) {
            throw new Exception('This script has already run');
        }
        
        $t = new xTransaction();
        $t->start();
            //Create new services
            $this->log("Add new rattachements");
                $this->addRattachements($t);   
            $this->log("Add of id_chuv to certains services");
                $this->addChuvIdToExistingRattachement($t);
            $this->log("Move persons from SYL to GE");
                $this->moveSYLpersonToGER($t);
            //Update name of existing services
            $this->log("Update name of existing services");
                $this->updateServicesName($t);
            //commission_membre model
            $this->log("Move CEPO commission members to 'oncologie'");
                $this->moveCEPOcommissionMembre($t);
            //personne_activite model
            $this->log("Move persons from CePO to different 'oncologie' services");
                $this->moveCEPOpersonToOncologie($t);
            //deletation of old services (soft-delete)
            $this->log("Delete (soft delete) services");
                $this->deleteServices($t);
        $t->end();
    }

    function addRattachements(xTransaction $t) {
        // Inserts 2 new positions
        $put = array(
            xModel::load('rattachement', array(
                'id' => 168,
                'id_chuv' => 'DDO',
                'actif' => 1,
                'section_id' => '1',
                'nom' => "Direction du Département d'oncologie (DO)",
                'abreviation' => 'DDO'
            )),
            xModel::load('rattachement', array(
                'id' => 169,
                'id_chuv' => 'ONM',
                'actif' => 1,
                'section_id' => '1',
                'nom' => "Service d'oncologie médicale",
                'abreviation' => 'ONM'
            )),
            xModel::load('rattachement', array(
                'id' => 170,
                'id_chuv' => 'PDO',
                'section_id' => '1',
                'nom' => "Plateformes du Département d'oncologie",
                'abreviation' => 'PDO'
            )),
            xModel::load('rattachement', array(
                'id' => 171,
                'id_unil' => null,
                'id_chuv' => null,
                'actif' => '1',
                'section_id' => '2',
                'nom' => "École de formation postgraduée",
                'abreviation' => 'EFPG'
            )),
            xModel::load('rattachement', array(
                'id' => 172,
                'id_chuv' => 'GER',
                'actif' => '1',
                'section_id' => '1',
                'nom' => 'Service de gériatrie et réadaptation gériatrique',
                'abreviation' => 'GER',
            )),
            xModel::load('rattachement', array(
                'id' => 173,
                'id_chuv' => 'CVA',
                'actif' => '1',
                'section_id' => '1',
                'nom' => 'Division de chirurgie vasculaire',
                'abreviation' => 'CVA',
            )),
            xModel::load('rattachement', array(
                'id' => 174,
                'id_chuv' => 'MEM',
                'actif' => '1',
                'section_id' => '1',
                'nom' => 'Centre Leenaards de la mémoire',
                'abreviation' => 'MEM',
            )),
        );
        //
        foreach ($put as $model) $t->execute($model, 'put');
    }
    
    function addChuvIdToExistingRattachement(xTransaction $t){
        // Inserts 3 new id_chuv which not enter durning previous insert
        $post = array(
            xModel::load('rattachement', array(
                'id' => 168,
                'id_chuv' => 'DDO',
            )),
            xModel::load('rattachement', array(
                'id' => 169,
                'id_chuv' => 'ONM',
            )),
            xModel::load('rattachement', array(
                'id' => 170,
                'id_chuv' => 'PDO',
            )),
        );
        //
        foreach ($post as $model) $t->execute($model, 'post');
    }
    
    function moveSYLpersonToGER(xTransaction $t){        
        $post = array(
            xModel::load('personne_activite', array(
            'id' => 95,
            'rattachement_id' => 172,
        )),
        xModel::load('personne_activite', array(
            'id' => 974,
            'rattachement_id' => 172,
        )),
        );
        //
        foreach ($post as $model) $t->execute($model, 'post');
    }
    
    function moveCEPOpersonToOncologie(xTransaction $t){
        //ONM id = 169
        //DDO id = 168
        //PDO id = 170
        //NCH id = 105
        $post = array(
            //Ketterer Nicolas - Privat-docent
            //Utilisateur inactif
            /*xModel::load('personne_activite', array(
                'id' => 355,
                'rattachement_id' => xx,
            )),*/
            //Leyvraz Serge - PO
            xModel::load('personne_activite', array(
                'id' => 388,
                'rattachement_id' => 169,
            )),
            //Michielin Olivier - PAS
            xModel::load('personne_activite', array(
                'id' => 447,
                'rattachement_id' => 169,
            )),
            //Petrova Tatiana - PAS boursier fns
            xModel::load('personne_activite', array(
                'id' => 509,
                'rattachement_id' => 168,
            )),
            //Ruegg Curzio - PAS
            //Utilisateur innactif
            /*xModel::load('personne_activite', array(
                'id' => 586,
                'rattachement_id' => xx,
            )),*/
            //Stupp Roger - PAS
            xModel::load('personne_activite', array(
                'id' => 659,
                'rattachement_id' => 105,
            )),
            //Leyvraz Serge - Médecin chef de service MCS
            xModel::load('personne_activite', array(
                'id' => 1009,
                'rattachement_id' => 169,
            )),
            //Wagner Anna Dorotea - Maître d'enseignement et de recherche 1
            xModel::load('personne_activite', array(
                'id' => 1105,
                'rattachement_id' => 169,
            )),
            //Wagner Anna Dorothea - PD
            xModel::load('personne_activite', array(
                'id' => 1106,
                'rattachement_id' => 169,
            )),
            //Wagner Anna Dorothea - Chef de clinique
            xModel::load('personne_activite', array(
                'id' => 1107,
                'rattachement_id' => 169,
            )),
            //Peters Solange - Médecin associé
            xModel::load('personne_activite', array(
                'id' => 1110,
                'rattachement_id' => 169,
            )),
            //Peters Solange - PD
            xModel::load('personne_activite', array(
                'id' => 1111,
                'rattachement_id' => 169,
            )),
            //Peters Solange - Maître d'enseignement et de recherche 1
            xModel::load('personne_activite', array(
                'id' => 1122,
                'rattachement_id' => 169,
            )),
            //Leyvraz Serge - PH
            xModel::load('personne_activite', array(
                'id' => 1138,
                'rattachement_id' => 169,
            )),
            //Foukas Periklis - Prof invité
            xModel::load('personne_activite', array(
                'id' => 1143,
                'rattachement_id'=>  170,
            )),
            
        );
        //
        foreach ($post as $model) $t->execute($model, 'post');
    }
    
    function moveCEPOcommissionMembre(xTransaction $t){
        $post = array(
            xModel::load('commission_membre', array(
                'id' => 10,
                'rattachement_id' => 169,
            )),
        );
        foreach ($post as $model) $t->execute($model, 'post');
    }
    
    function deleteServices(xTransaction $t){
        //Soft delete
        //WARNING you cannot use the shortcut id => array('id','id','id')
        //it works but it will be not versionned
        $post = array(
            xModel::load('rattachement', array(
                'id' => 61,
                'actif' => 0
            )),
            xModel::load('rattachement', array(
                'id' => 128,
                'actif' => 0
            )),
            xModel::load('rattachement', array(
                'id' => 138,
                'actif' => 0
            )),
        );
        foreach ($post as $model) $t->execute($model, 'post');
    }
    
    function updateServicesName(xTransaction $t){
        $post = array(
            xModel::load('rattachement', array(
                'id' => 63,
                'nom' => 'Service de chirurgie cardiaque'
            )),
            xModel::load('rattachement', array(
                'id' => 119,
                'nom' => 'Service de médecine nucléaire et imagerie moléculaire'
            )),
            xModel::load('rattachement', array(
                'id' => 100,
                'nom' => 'Service de chirurgie plastique et de la main'
            )),
        );
        foreach ($post as $model) $t->execute($model, 'post');
    }

    /**
     * Returns true if the rows to create already exist.
     * This means the script has already been run on this instance.
     */
    function already_run() {
        $r = xModel::load('rattachement', array(
            'id' => array(172, 173,174)
        ))->get();
        return !!$r;
    }
}

new iafbmIssue246();