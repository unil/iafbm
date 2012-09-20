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
        $this->create_fields__candidats($t);
        $this->create_fields__commissions($t);
        $this->create_table__grandeurs($t);
        $this->populate_table__grandeurs($t);
        $this->create_table__commissions_propositions_nominations($t);
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