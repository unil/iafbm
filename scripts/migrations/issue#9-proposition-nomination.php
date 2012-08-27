<?php

require_once(dirname(__file__).'/../Script.php');

class iafbmIssue9 extends iafbmScript {

    function run() {
        // Single run test
        if ($this->already_run()) {
            throw new Exception('This script has already run');
        }
        //
        $this->execute_sql_file('191_commissions_propositions_nominations.sql');
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