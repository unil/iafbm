<?php

require_once(dirname(__file__).'/../Script.php');

class iafbmIssue243 extends iafbmScript {

    function run() {
        // Single run test
        if ($this->already_run()) {
            throw new Exception('This script has already run');
        }
        
        try {
            $t = new xTransaction();
            $t->start();
                $this->create_evaluations_types($t);
                $this->create_evaluations_etats($t);
                $this->create_evaluations($t);
                $this->create_evaluations_apercus($t);
                $this->create_evaluations_evaluateurs($t);
                $this->create_evaluations_decisions($t);
                $this->create_evaluations_evaluations($t);
                $this->create_evaluations_cdirs($t);
                $this->create_evaluations_contrats($t);
                $this->create_evaluations_rapports($t);
                
                $this->populateTables($t);
            $t->end();
        } catch (Exception $e) {
            // Removes CREATED TABLES
            // that does not rollbacks
            xModelMysql::q("DROP TABLE IF EXISTS
                                evaluations_apercus,
                                evaluations_evaluateurs,
                                evaluations_evaluations,
                                evaluations_cdirs,
                                evaluations_contrats,
                                evaluations_rapports,
                                evaluations,
                                evaluations_types,
                                evaluations_etats,
                                evaluations_decisions;
            ");
            throw $e;
        }
    }
    
    function populateTables(xTransaction $t){
        require(xContext::$basepath."/../sql/900_catalogue_data.php");
        
        // Populates evaluations_etats table
        foreach ($catalogue_data['evaluation_etat'] as $item) {
            $t->execute(xModel::load('evaluation_etat', $item), 'put');
        }
        
        // Populates evaluations_types table        
        foreach ($catalogue_data['evaluation_type'] as $item) {
            $t->execute(xModel::load('evaluation_type', $item), 'put');
        }
        
        // Populates evaluations_etats table
        foreach ($catalogue_data['evaluation_decision'] as $item) {
            $t->execute(xModel::load('evaluation_decision', $item), 'put');
        }
    }
    
    function create_evaluations_types(xTransaction $t) {
        // Creates evaluations_types table
        $t->execute_sql("
            CREATE  TABLE evaluations_types (
                id INT NOT NULL AUTO_INCREMENT,
                actif BOOLEAN NOT NULL DEFAULT true,
                type VARCHAR(20) NOT NULL,
                PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");
    }
    
    function create_evaluations_etats(xTransaction $t) {
        // Creates evaluations_etats table
        $t->execute_sql("
            CREATE  TABLE evaluations_etats (
                id INT NOT NULL AUTO_INCREMENT,
                actif BOOLEAN NOT NULL DEFAULT true,
                etat VARCHAR(45) NOT NULL,
                PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");
    }
    
    function create_evaluations(xTransaction $t) {
        // Creates evaluations table
        $t->execute_sql("
            CREATE TABLE evaluations (
                id int(11) NOT NULL AUTO_INCREMENT,
                actif tinyint(1) NOT NULL DEFAULT '1',
                termine tinyint(4) NOT NULL DEFAULT '0',
                evaluation_type_id int(11) NOT NULL,
                date_periode_debut date DEFAULT NULL,
                date_periode_fin date DEFAULT NULL,
                personne_id int(11) NOT NULL,
                activite_id int(11) NOT NULL,
                evaluation_etat_id int(11) NOT NULL DEFAULT '1',
                PRIMARY KEY (id),
                FOREIGN KEY (evaluation_type_id) REFERENCES evaluations_types (id),
                FOREIGN KEY (activite_id) REFERENCES activites (id),
                FOREIGN KEY (personne_id) REFERENCES personnes (id) 
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");
    }
    
    function create_evaluations_apercus(xTransaction $t) {
        // Creates evaluations_apercus table
        $t->execute_sql("
            CREATE  TABLE evaluations_apercus (
                id INT NOT NULL AUTO_INCREMENT ,
                actif BOOLEAN NOT NULL DEFAULT true,
                termine BOOLEAN NOT NULL DEFAULT false,
                evaluation_id INT NOT NULL,
                commentaire TEXT,
                PRIMARY KEY (id),
                FOREIGN KEY (evaluation_id) REFERENCES evaluations(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");
    }
    
    function create_evaluations_evaluateurs(xTransaction $t) {
        // Creates evaluations_evaluateurs table
        $t->execute_sql("
            CREATE  TABLE evaluations_evaluateurs (
                id INT NOT NULL AUTO_INCREMENT ,
                actif BOOLEAN NOT NULL DEFAULT true,
                evaluation_id INT NOT NULL,
                personne_id INT NOT NULL,
                PRIMARY KEY (id),
                FOREIGN KEY (evaluation_id) REFERENCES evaluations(id),
                FOREIGN KEY (personne_id) REFERENCES personnes(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");
    }
    
    function create_evaluations_decisions(xTransaction $t) {
        // Creates evaluations_decisions table
        $t->execute_sql("
            CREATE  TABLE evaluations_decisions (
                id INT NOT NULL AUTO_INCREMENT ,
                actif BOOLEAN NOT NULL DEFAULT true,
                decision VARCHAR(45) NOT NULL,
                commentaire VARCHAR(45) DEFAULT NULL,
                PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");
    }
    
    function create_evaluations_evaluations(xTransaction $t) {
        // Creates evaluations_evaluations table
        $t->execute_sql("
            CREATE  TABLE evaluations_evaluations (
                id INT NOT NULL AUTO_INCREMENT ,
                actif BOOLEAN NOT NULL DEFAULT true,
                termine BOOLEAN NOT NULL DEFAULT false,
                evaluation_id INT NOT NULL,
                date_rapport_evaluation DATE DEFAULT NULL,
                preavis_evaluateur_id INT DEFAULT NULL,
                preavis_decanat_id INT DEFAULT NULL,
                date_liste_transmise DATE DEFAULT NULL,
                date_dossier_transmis DATE DEFAULT NULL,
                commentaire TEXT,
                PRIMARY KEY (id),
                FOREIGN KEY (evaluation_id) REFERENCES evaluations(id),
                FOREIGN KEY (preavis_evaluateur_id) REFERENCES evaluations_decisions(id),
                FOREIGN KEY (preavis_decanat_id) REFERENCES evaluations_decisions(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");
    }
    
    function create_evaluations_cdirs(xTransaction $t) {
        // Creates evaluations_cdirs table
        $t->execute_sql("
            CREATE  TABLE evaluations_cdirs (
                id INT NOT NULL AUTO_INCREMENT ,
                actif BOOLEAN NOT NULL DEFAULT true,
                termine BOOLEAN NOT NULL DEFAULT false,
                evaluation_id INT NOT NULL,
                seance_cdir DATE DEFAULT NULL,
                commentaire TEXT,
                decision_id INT NOT NULL DEFAULT 1,
                PRIMARY KEY (id),
                FOREIGN KEY (evaluation_id) REFERENCES evaluations(id),
                FOREIGN KEY (decision_id) REFERENCES evaluations_decisions(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");
    }
    
    function create_evaluations_contrats(xTransaction $t) {
        // Creates evaluations_contrats table
        $t->execute_sql("
            CREATE  TABLE evaluations_contrats (
                id INT NOT NULL AUTO_INCREMENT ,
                actif BOOLEAN NOT NULL DEFAULT true,
                termine BOOLEAN NOT NULL DEFAULT false,
                evaluation_id INT NOT NULL,
                copie_nouveau_contrat BOOLEAN DEFAULT NULL,
                commentaire TEXT,
                PRIMARY KEY (id),
                FOREIGN KEY (evaluation_id) REFERENCES evaluations(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");
    }
    
    function create_evaluations_rapports(xTransaction $t) {
        // Creates evaluations_rapports table
        $t->execute_sql("
            CREATE  TABLE evaluations_rapports (
                id INT NOT NULL AUTO_INCREMENT ,
                actif BOOLEAN NOT NULL DEFAULT true,
                termine BOOLEAN NOT NULL DEFAULT false,
                evaluation_id INT NOT NULL,
                date_biblio_demandee date DEFAULT NULL,
                date_biblio_recue DATE DEFAULT NULL,
                date_relance DATE DEFAULT NULL,
                date_rapport_recu DATE DEFAULT NULL,
                date_transmis_evaluateur DATE DEFAULT NULL,
                date_entretien DATE DEFAULT NULL,
                date_accuse_lettre DATE DEFAULT NULL,
                date_accuse_email DATE DEFAULT NULL,
                commentaire TEXT,
                PRIMARY KEY (id),
                FOREIGN KEY (evaluation_id) REFERENCES evaluations(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");
    }

    /**
     * Returns true if the fields to create already exist.
     * This means the script has already been run on this instance.
     */
    function already_run() {
        $fields = array('dg_commentaire_commentaire');  // Last field added
        $r = xModel::q('show tables like "evaluations%";');
        
        $already_run = (mysql_num_rows($r) == false) ? false : true;
        return $already_run;
    }
}

new iafbmIssue243();