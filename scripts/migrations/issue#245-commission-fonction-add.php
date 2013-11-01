<?php

require_once(dirname(__file__).'/../Script.php');

/**
 * @package scripts-migration
 */
class iafbmIssue245 extends iafbmScript {

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
        // Inserts 2 new positions
        $put = array(
            xModel::load('commission_fonction', array(
                'id' => 12,
                'actif' => '1',
                'nom' => 'Autre',
                'description' => 'Autre',
                'position' => '12'
            )),
            xModel::load('commission_fonction', array(
                'id' => 13,
                'actif' => '1',
                'nom' => 'Délégué facultaire permanent dans les commissions de titularisation FBM',
                'description' => 'Délégué facultaire permanent dans les commissions de titularisation FBM',
                'position' => '13'
            )),
        );
        //
        foreach ($put as $model) $t->execute($model, 'put');
    }

    /**
     * Returns true if the fields to create already exist.
     * This means the script has already been run on this instance.
     */
    function already_run() {
        $r = xModel::load('commission_fonction', array(
            'id' => array(12, 13)
        ))->get();
        return !!$r;
    }
}

new iafbmIssue245();