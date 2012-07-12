<?php

require_once(dirname(__file__).'/../Script.php');

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
        $put = array(
            xModel::load('commission_fonction', array(
                'id' => 10,
                'actif' => '1',
                'nom' => 'A entendre',
                'description' => 'Personne à entendre',
                'position' => ?
            )),
            xModel::load('commission_fonction', array(
                'id' => 11,
                'actif' => '1',
                'nom' => 'Représentant FHV',
                'description' => 'Représentant FHV',
                'position' => ?
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
            'id' => array(10, 11)
        ))->get();
        return !!$r;
    }
}

new iafbmIssue191();