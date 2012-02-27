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

    protected function create_personnes_unil() {
        $data = $this->read_file_personnes_unil();
        $this->insert('personne', $data);
    }

    protected function create_personnes_chuv() {
        $data = $this->read_file_personnes_chuv();
        $this->insert('personne', $data);
    }

    /**
     * Returns the file contents as an array of lines.
     */
    protected function read_file($filename) {
        $stream = @file_get_contents($filename);
        if (!$stream) throw new xException("CSV file is empty or not found ({$filename})");
        $lines = explode("\n", $stream);
        return $lines;
    }

    /**
     * Returns a PHP data array representing the CSV data.
     */
    protected function read_file_personnes_unil() {
        $this->log('Parsing UNIL data file...');
        // Defines fields names and order
        $fields = array('id_unil', 'nom', 'prenom', '...');
        // Create data array
        $lines = $this->read_file('unil.csv');
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
        $lines = $this->read_file('chuv.csv');
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