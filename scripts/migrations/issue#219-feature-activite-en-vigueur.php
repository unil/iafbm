<?php

require_once(dirname(__file__).'/../Script.php');

class iafbmIssue219 extends iafbmScript {

    function run() {
        // Single run test
        if ($this->already_run()) {
            throw new Exception('This script has already run');
        }
        //
        $t = new xTransaction();
        $t->start();
        $this->update_fields($t);
        $t->end();
    }

    function update_fields(xTransaction $t) {
        $statements = array(
            'ALTER TABLE iafbm.personnes_activites ADD COLUMN en_vigueur BOOLEAN NOT NULL DEFAULT TRUE'
        );
        foreach ($statements as $statement) $t->execute_sql($statement);
    }

    /**
     * Returns true if the fields to create already exist.
     * This means the script has already been run on this instance.
     */
    function already_run() {
        $fields = array('en_vigueur');
        $r = xModel::q('DESCRIBE personnes_activites');
        while ($row = mysql_fetch_assoc($r)) {
            $result[] = $row['Field'];
        }
        return !!array_intersect($result, $fields);
    }
}

new iafbmIssue219();