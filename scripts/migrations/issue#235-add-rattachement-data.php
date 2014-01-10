<?php

require_once(dirname(__file__).'/../Script.php');

class iafbmIssue235 extends iafbmScript {
    
    function run() {
        // Single run test
        if ($this->already_run()) {
            throw new Exception('This script has already run');
        }
        //DB update
        $t = new xTransaction();
        $t->start();
        $this->add_records($t);
        $t->end();
    }

    function add_records(xTransaction $t) {
        // Adds:
        //      SSC
        //          - HEM (Service d'hématologie)
        //          - RTH (Service de radio-oncologie) 
        //          - DDO (Direction du Dpt d'oncologie (DO))
        //          - ONM (Service d'oncologie médicale) 
        //          - PDO (Plateformes du Département d'oncologie)
        //      SSF
        //          - École de formation postgraduée
        
        $put = array(
            //SSC
            // Commented models were already in production database.
            /*xModel::load('rattachement', array(
                'actif' => 1,
                'id_unil' => null,
                'id_chuv' => null,
                'section_id' => 1,
                'nom' => "Service d'hématologie",
                'abreviation' => 'HEM'
            )),
            xModel::load('rattachement', array(
                'actif' => 1,
                'id_unil' => null,
                'id_chuv' => null,
                'section_id' => 1,
                'nom' => "Service de radio-oncologie",
                'abreviation' => 'RTH'
            )),*/
            xModel::load('rattachement', array(
                'actif' => 1,
                'id_unil' => null,
                'id_chuv' => null,
                'section_id' => 1,
                'nom' => "Direction du Département d'oncologie (DO)",
                'abreviation' => 'DDO'
            )),
            xModel::load('rattachement', array(
                'actif' => 1,
                'id_unil' => null,
                'id_chuv' => null,
                'section_id' => 1,
                'nom' => "Service d'oncologie médicale",
                'abreviation' => 'ONM'
            )),
            xModel::load('rattachement', array(
                'actif' => 1,
                'id_unil' => null,
                'id_chuv' => null,
                'section_id' => 1,
                'nom' => "Plateformes du Département d'oncologie",
                'abreviation' => 'PDO'
            )),
            //SSF
            // IMPORTANT NOTE: "Ecole de formation postgraduée" is an SSC section but for IAFBM convention, this is in SSF
            //                  same remark with "Ecole de médecine" which not added in this script.
            xModel::load('rattachement', array(
                'actif' => 1,
                'id_unil' => null,
                'id_chuv' => null,
                'section_id' => 2,
                'nom' => "École de formation postgraduée",
                'abreviation' => 'EFPG'
            )),
        );
        //
        foreach ($put as $model) $t->execute($model, 'put');
    }

    /**
     * Returns true if the data already exist.
     * This means the script has already been run on this instance.
     */
    function already_run() {
        $rows = xModel::load('rattachement', array(
            // Commented models were already in production database.
            'abreviation' => array(/*'HEM', 'RTH', */'DDO', 'ONM', 'PDO', 'EFPG')
        ))->get();
        //Display which "rattachements" are already in DB
        if(!!$rows){
            echo "Les rattachements suivants existent déjà:\n";
            foreach ($rows as $r) echo "\t- ".$r['abreviation']."\n";
        }
        return !!$rows;
    }
}

new iafbmIssue235();