<?php

require_once(dirname(__file__).'/../Script.php');

class iafbmIssue9 extends iafbmScript {

    function run() {
        // Single run test
        if ($this->already_run()) {
            throw new Exception('This script has already run');
        }
        $t = new xTransaction();
        $t->start();
        $this->log("Creating additional fields");
        $this->log("Processing table 'candidats'", 1);
        $this->create_fields__candidats($t);
        $this->log("Processing table 'commission'", 1);
        $this->create_fields__commissions($t);
        $this->log("Creating additional table 'grandeur'");
        $this->log("Creating", 1);
        $this->create_table__grandeurs($t);
        $this->log("Populating", 1);
        $this->populate_table__grandeurs($t);
        $this->log("Creating additional table 'commission");
        $this->log("Creating", 1);
        $this->create_table__commissions_propositions_nominations($t);
        $this->log("Populating", 1);
        $this->populate_table__commissions_propositions_nominations($t);
        $t->end();
    }


    /**
     * Creates table 'grandeurs'
     */
    function create_table__grandeurs(xTransaction $t) {
        $this->execute_sql_file('005_grandeurs.sql', $t);
    }

    /**
     * Populates table 'grandeurs'
     */
    function populate_table__grandeurs(xTransaction $t) {
        include(xContext::$basepath.'/../sql/900_catalogue_data.php');
        $data = $catalogue_data['grandeur'];
        foreach ($data as $record) {
            $t->execute(xModel::load('grandeur', $record), 'put');
        }
    }

    /**
     * Creates table 'commissions_propositions_nominations'
     */
    function create_table__commissions_propositions_nominations(xTransaction $t) {
        $this->execute_sql_file('191_commissions_propositions_nominations.sql', $t);
    }

    /**
     * Populates table 'commissions_propositions_nominations'
     * by creating a default row for each commission row.
     */
    function populate_table__commissions_propositions_nominations(xTransaction $t) {
        $items = xModel::load('commission')->get();
        foreach ($items as $item) {
            $t->execute(xModel::load('commissions_propositions_nominations', array(
                'commission_id' => $item['id']
            )));
        }
    }

    /**
     * Adds fields to table 'candidats'
     */
    function create_fields__commissions(xTransaction $t) {
        $t->execute_sql('ALTER TABLE commissions ADD COLUMN institut TEXT AFTER nom');
    }

    /**
     * Adds fields to table 'candidats'
     */
    function create_fields__candidats(xTransaction $t) {
        $t->execute_sql('ALTER TABLE candidats ADD COLUMN personne_denomination_id INT AFTER genre_id');
        $t->execute_sql('ALTER TABLE candidats ADD FOREIGN KEY (personne_denomination_id) REFERENCES personnes_denominations(id)');
        //
        $t->execute_sql('ALTER TABLE candidats ADD COLUMN canton_id INT AFTER pays_id');
        $t->execute_sql('ALTER TABLE candidats ADD FOREIGN KEY (canton_id) REFERENCES cantons(id)');
        //
        $t->execute_sql('ALTER TABLE candidats ADD COLUMN permis_id INT AFTER canton_id');
        $t->execute_sql('ALTER TABLE candidats ADD FOREIGN KEY (permis_id) REFERENCES permis(id)');
    }

    /**
     * Returns true if the table to create already exists.
     * This means the script has already been run on this instance.
     */
    function already_run() {
        $r = xModel::q("SHOW TABLES LIKE 'commissions_propositions_nominations'");
        return mysql_num_rows($r);
    }

}

new iafbmIssue9();