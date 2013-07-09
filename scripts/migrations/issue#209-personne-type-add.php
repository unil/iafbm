<?php

require_once(dirname(__file__).'/../Script.php');

/**
 * @package scripts-migration
 */
class iafbmIssue191 extends iafbmScript {

    function run() {
        // Single run test
        if ($this->already_run()) {
            throw new Exception('This script has already run');
        }
        //
        $t = new xTransaction();
        $t->start();
        $this->update_records($t);
        $t->end();
    }

    function update_records(xTransaction $t) {
        // Inserts 1 new item
        $t->execute(xModel::load('personne_type', array(
            'id' => 4,
            'actif' => '1',
            'nom' => 'Inactif',
        )), 'put');
    }

    /**
     * Returns true if the fields to create already exist.
     * This means the script has already been run on this instance.
     */
    function already_run() {
        $r = xModel::load('personne_type', array(
            'id' => array(4)
        ))->get();
        return !!$r;
    }
}

new iafbmIssue191();