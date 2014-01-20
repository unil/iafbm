<?php

require_once(dirname(__file__).'/../Script.php');

class iafbmIssue235 extends iafbmScript {
    
    function run() {
        // Single run test
        if ($this->already_run()) {
            throw new Exception('This script has already run');
        }
        //DB update
        try {
            $t = new xTransaction();
            $t->start();
                $this->createScriptDeltaChuv($t);
                echo "Table crée avec succès\n\n";
            $t->end();
        } catch (Exception $e) {
            echo "There is a problem to create table script_deltaChuv";
            var_dump($e);
        }
        
    }

    public function createScriptDeltaChuv(xTransaction $t){
        // Creates commissions_creations_etats table
        $t->execute_sql("
            CREATE TABLE scripts_deltaChuv (
              id int(11) NOT NULL AUTO_INCREMENT,
              modif_id int(11) NOT NULL COMMENT 'Identificateur de la modification récupérée dans noeud modifId du fichier XML de la modification',
              operation varchar(45) NOT NULL COMMENT 'C = création d''un service en table\nS = suppresion d''un service en table\nU = modification d''un libellé de service',
              log text NOT NULL COMMENT 'Log admin de la modification',
              date date NOT NULL COMMENT 'Date à laquelle la modification a été appliquée en table',
              PRIMARY KEY (`id`),
              UNIQUE KEY `modif_id_UNIQUE` (`modif_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        return $t;
    }


    /**
     * Returns true if the data already exist.
     * This means the script has already been run on this instance.
     */
    function already_run() {
        $result = array();
        $r = xModel::q("SHOW TABLES LIKE '%scripts_deltaChuv%';");
        while ($row = mysql_fetch_assoc($r)) {
            $result[] = $row['Field'];
        }
        return !!$result;
    }
}

new iafbmIssue235();