<?php

require_once(dirname(__file__).'/../Script.php');
require_once(dirname(__file__).'/vendors/parsecsv-0.3.2/parsecsv.lib.php');

class iafbmIssue212 extends iafbmScript {

    function run() {
        // Single run test
        if ($this->already_run()) {
            throw new Exception('This script has already run');
        }
        //
        $this->update_adresses();
    }

    function update_adresses() {
        $this->log('Updating adresses...');
        $vi = xModel::load('version')->current();
        $this->log("Starting version id: {$vi}", 1);
        // Parses CSV data
        $file = $file = xContext::$basepath."/../import/francine/export_IAFBM.csv";
        $csv = new parseCSV();
        $csv->auto($file);
        $data = $csv->data;
        // Updates data
        $t = new xTransaction();
        $t->start();
        foreach ($data as $item) {
            $t->execute(xModel::load('adresse', array(
                'id' => $item['adresse_id'],
                'adresse_type_id' => $item['adresse_adresse_type_id'],
                'rue' => $item['adresse_rue'],
                'npa' => $item['adresse_npa'],
                'lieu' => $item['adresse_lieu'],
                'pays_id' => $item['adresse_pays_id'],
            )), 'post');
            $t->execute(xModel::load('personne_adresse', array(
                'id' => $item['id'],
                'defaut' => $item['defaut'],
            )), 'post');
        }
        $t->end();
        // Summary
        $vf = xModel::load('version')->current();
        $this->log("Ending version id: {$vf}", 1);
        $delta = xModel::load('version_data', array(
            'version_id' => $vi,
            'version_id_comparator' => '>',
            'version_model_name' => array(
                'adresse',
                'personne_adresse'
            )
        ))->get();
        foreach ($delta as $item) {
            $this->log("Modele:{$item['version_model_name']}, id:{$item['version_id_field_value']}, champs:{$item['field_name']}");
            $this->log("Ancienne valeur:", 1);
            $this->log("{$item['old_value']}", 2);
            $this->log("Nouvelle valeur:", 1);
            $this->log("{$item['new_value']}", 2);
            $this->log();
        }
        $this->log('Done.');
    }


    /**
     * Returns true if the fields to create already exist.
     * This means the script has already been run on this instance.
     */
    function already_run() {
        // FIXME:
        return false;
    }
}

new iafbmIssue212();