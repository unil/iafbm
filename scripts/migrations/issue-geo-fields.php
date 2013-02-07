<?php

require_once(dirname(__file__).'/../Script.php');

class iafbmIssueGeoFields extends iafbmScript {

    function run() {
        // Single run test
        if ($this->already_run()) {
            throw new Exception('This script has already run');
        }
        //
        $this->create_fields();
        $this->geocode_existing_adresses();
    }

    function create_fields() {
        $statements = array(
            'ALTER TABLE adresses ADD COLUMN geo_x FLOAT DEFAULT NULL AFTER pays_id',
            'ALTER TABLE adresses ADD COLUMN geo_y FLOAT DEFAULT NULL AFTER geo_x',
        );
        $t = new xTransaction();
        $t->start();
        foreach ($statements as $statement) xModel::q($statement);
        $t->end();
    }

    function geocode_existing_adresses() {
        $ids = xModel::load('adresse', array(
            'xreturn' => 'id',
            'geo_x' => null,
            'geo_y' => null,
            'geo_y_operator' => 'OR'
        ))->get();
        foreach ($ids as $id) {
            xModel::load('adresse', array('id' => $id))->post();
        }
    }

    /**
     * Returns true if the fields to create already exist.
     * This means the script has already been run on this instance.
     */
    function already_run() {
        $fields = array('geo_x', 'geo_y');
        $r = xModel::q('DESCRIBE adresses');
        while ($row = mysql_fetch_assoc($r)) {
            $result[] = $row['Field'];
        }
        return !!array_intersect($result, $fields);
    }
}

new iafbmIssueGeoFields();
