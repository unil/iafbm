<?php

require_once(dirname(__file__).'/../Script.php');

class iafbmIssue9 extends iafbmScript {

    function run() {
        // Single run test
        if ($this->already_run()) {
            throw new Exception('This script has already run');
        }
        //
        $this->create_fields();
    }

    function create_fields() {
        $statements = array(
            TODO
            'ALTER TABLE iafbm.commissions_membres ADD COLUMN fonction_complement TEXT AFTER commission_fonction_id',
            'ALTER TABLE iafbm.commissions_membres ADD COLUMN personne_denomination_id INT AFTER fonction_complement',
            'ALTER TABLE iafbm.commissions_membres ADD FOREIGN KEY (personne_denomination_id) REFERENCES personnes_denominations(id)',
        );
        $t = new xTransaction();
        $t->start();
        foreach ($statements as $statement) xModel::q($statement);
        $t->end();
    }

    /**
     * Returns true if the fields to create already exist.
     * This means the script has already been run on this instance.
     */
    function already_run() {
        $fields = array( TODO 'fonction_complement', 'personne_denomination_id');
        $r = xModel::q('DESCRIBE commissions_membres');
        while ($row = mysql_fetch_assoc($r)) {
            $result[] = $row['Field'];
        }
        return !!array_intersect($result, $fields);
    }
}

new iafbmIssue9();