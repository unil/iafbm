<?php

require_once(dirname(__file__).'/../Script.php');

/**
 * @package scripts-migration
 */
class iafbmIssue9 extends iafbmScript {

    function run() {
        // Single run test
        if ($this->already_run()) {
            throw new Exception('This script has already run');
        }
        $t = new xTransaction();
        $t->start();
        $this->log("Fixing unwanted NULL foreign fields");
        $this->fix_null_foreign_fields($t);
        $t->end();
    }


    /**
     * Adds fields to table 'candidats'
     */
    function fix_null_foreign_fields(xTransaction $t) {
        // git diff sql/*
        // to find out which tables fields to alter
        $modifications = array(
            //'table' => array(
            //    'myfield1 INT NOT',
            //    'myfield2 INT NOT'
            //),
            'activites' => array(
                'section_id INT',
                'activite_type_id INT',
                'activite_nom_id INT'
            ),
            'rattachements' => array(
                'section_id INT',
            ),
            'personnes_activites' => array(
                'personne_id INT',
                'activite_id INT',
                'rattachement_id INT',
            ),
            'personnes_adresses' => array(
                'personne_id INT',
                'adresse_id INT',
            ),
            'personnes_emails' => array(
                'personne_id INT',
            ),
            'personnes_telephones' => array(
                'personne_id INT',
            ),
            'candidats' => array(
                'commission_id INT',
            ),
        );
        // Remove impacted records (records with unwanted NULL values)
        // and cleans versioning
        foreach ($modifications as $table => $definitions) {
            // Fetches ids of records to delete
            $sql = "SELECT id FROM {$table} WHERE 1=0 ";
            foreach ($definitions as $definition) {
                $column_name = @array_shift(explode(' ', $definition));
                $sql .= "OR {$column_name} IS NULL ";
            }
            // Creates an array of ids to delete
            $result = xModel::load('pays')->query($sql);
            $ids = array();
            foreach ($result as $record) $ids[] = $record['id'];
            if (!$ids) continue;
            // Deletes records (no soft-delete)
            $in = implode(', ', $ids);
            $sql = "DELETE FROM {$table} WHERE id IN ($in)";
            $this->log("Cleaning null records", 1);
            $this->log("ids: {$in}", 2);
            $t->execute_sql($sql);
            // Retrives impacted versions ids
            // Disabled for peace of mind: it is better to have versions that belong to no record
            //                             that record that have lost their versions.
            /*
            $sql = "SELECT id FROM versions WHERE table_name = '{$table}' AND id_field_value in ({$in})";
            $versions = xModel::load('pays')->query($sql);
            $ids = array();
            foreach ($versions as $version) $ids[] = $version['id'];
            $in = implode(', ', $ids);
            $this->log("Cleaning versioning", 1);
            $this->log("ids: {$in}", 2);
            // Deletes versioning relations
            $sql = "DELETE FROM versions_relations WHERE version_id in ({$in})";
            $result = $t->execute_sql($sql);
            // Deletes versioning data
            $sql = "DELETE FROM versions_data WHERE version_id in ({$in})";
            $t->execute_sql($sql);
            // Deletes versioning
            $sql = "DELETE FROM versions WHERE id in ({$in})";
            $t->execute_sql($sql);
            */
        }
        //
        //
        // Modifies columns definition as NOT NULL
        $this->log("Updating table structure");
        foreach ($modifications as $table => $definitions) {
            foreach ($definitions as $definition) {
                $this->log("Modifying {$table} {$definition}", 1);
                $sql = "ALTER TABLE {$table} MODIFY COLUMN {$definition} NOT NULL;";
                $t->execute_sql($sql);
            }
        }
    }

    /**
     * Returns true if the table to create already exists.
     * This means the script has already been run on this instance.
     */
    function already_run() {
        // FIXME
        return false;
    }

}

new iafbmIssue9();
