<?php

require_once(dirname(__file__).'/../Script.php');

/**
 * @package scripts-migration
 */
class iafbmIssue191b extends iafbmScript {

    function run() {
        // Single run test
        if ($this->already_run()) {
            throw new Exception('This script has already run');
        }
        //
        $t = new xTransaction();
        $t->start();
        $this->create_table($t);
        $t->end();
    }

    function create_table(xTransaction $t) {
        $file = xContext::$basepath.'/../sql/151_commissions_membres_nonominatifs.sql';
        $sql = file_get_contents($file);
        $statements = array_filter(explode(';', $sql));
        foreach ($statements as $statement) $t->execute_sql($statement);
    }

    /**
     * Returns true if the fields to create already exist.
     * This means the script has already been run on this instance.
     */
    function already_run() {
        $r = xModel::q("SHOW TABLES LIKE 'commissions_membres_nonominatifs'");
        return mysql_num_rows($r);
    }
}

new iafbmIssue191b();