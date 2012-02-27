<?php

require_once(dirname(__file__).'/Script.php');

class iafbmImportScript extends iafbmScript {

    function run() {
        $this->create_personnes_unil();
        $this->create_personnes_chuv();
    }

    protected function insert($modelname, $data) {
        foreach ($data as $item) xModel::load($modelname, $item)->put();
    }

    protected create_personnes_unil() {
        $data = $this->read_file_personnes_unil();
        $this->insert('personnes', $data);
    }

    protected create_personnes_chuv() {
        $data = $this->read_file_personnes_chuv();
        $this->insert('personnes', $data);
    }

    /**
     * Returns a PHP data array representing the CSV data.
     */
    protected function read_file_personnes_unil() {
        $this->log('Parsing UNIL data file...');
        // Defines fields names and order
        $fields = array('id_unil', 'nom', 'prenom', '...');
        // Create data array
        $stream = file_get_contents('file.csv');
        $lines = explode("\n", $stream);
        $data = array();
        foreach($lines as $line) {
            $values = explode(',', $line);
            $values = array_map('trim', $values); // Cleans values
            $data[] = array_combine($fields, $values);
        }
        return $data;
    }

    /**
     * Returns a PHP data array representing the CSV data.
     */
    protected function read_file_personnes_chuv() {
        $this->log('Parsing CHUV data file...');
        // Defines fields names and order
        $fields = array('id_unil', 'nom', 'prenom', '...');
        // Create data array
        $stream = file_get_contents('file.csv');
        $lines = explode("\n", $stream);
        $data = array();
        foreach($lines as $line) {
            $values = explode(',', $line);
            $values = array_map('trim', $values); // Cleans values
            $data[] = array_combine($fields, $values);
        }
        return $data;
    }
}

new iafbmImportScript();

?>