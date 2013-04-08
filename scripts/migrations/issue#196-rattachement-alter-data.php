<?php

require_once(dirname(__file__).'/../Script.php');

/**
 * @package scripts-migration
 */
class iafbmIssue196 extends iafbmScript {

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
        // Modifies DBCM => DNF
        $r = xModel::load('rattachement', array('abreviation' => 'DBCM'))->get(0);
        $id = $r['id'];
        if (!$id) throw new xException("Could not retrieve id for 'rattachement' DBCM");
        $t->execute(xModel::load('rattachement', array(
            'id' => $id,
            'abreviation' => 'DNF',
            'nom' => 'Département de neurosciences fondamentales'
        )), 'post');
        // Adds EB + EM
        $put = array(
            xModel::load('rattachement', array(
                'id' => 166,
                'actif' => 1,
                'id_unil' => null,
                'id_chuv' => null,
                'section_id' => 2,
                'nom' => 'Ecole de biologie',
                'abreviation' => 'EB'
            )),
            xModel::load('rattachement', array(
                'id' => 167,
                'actif' => 1,
                'id_unil' => null,
                'id_chuv' => null,
                'section_id' => 2,
                'nom' => 'Ecole de médecine',
                'abreviation' => 'EM'
            )),
        );
        //
        foreach ($put as $model) $t->execute($model, 'put');
    }

    /**
     * Returns true if the modified data already exist.
     * This means the script has already been run on this instance.
     */
    function already_run() {
        $r = xModel::load('rattachement', array(
            'id' => array(166, 167)
        ))->get();
        return !!$r;
    }
}

new iafbmIssue196();