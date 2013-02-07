<?php

require_once(dirname(__file__).'/../Script.php');

class iafbmIssue206 extends iafbmScript {


    function run() {
        // Single run test
        if ($this->already_run()) {
            throw new Exception('This script has already run');
        }
        // Confirmation
        $this->confirm("This action will fuse some commission_fonction. Are you sure?");
        // Data processing
        $t = new xTransaction();
        $t->start();
        $this->update_records($t);
        $t->end();
    }

    function update_records(xTransaction $t) {
        $this->log('Processing...');
        // Updates every commission_membre fonction id from 11 to 9
        // (included soft-deleted records)
        $membres = xModel::load('commission_membre', array(
            'actif' => array(0, 1),
            'commission_fonction_id' => 11,
            'xjoin' => ''
        ))->get();
        $count = count($membres);
        $this->log("Updating {$count} commission_membre", 1);
        foreach ($membres as $membre) {
            $t->execute(xModel::load('commission_membre', array(
                'id' => $membre['id'],
                'commission_fonction_id' => 9,
            )), 'post');
        }
        // Removes commission_fonction 'invité permanant' and 'à entendre' (soft-delete)
        $this->log("Removing commission_fonction 'A entendre'", 1);
        $t->execute(xModel::load('commission_fonction', array(
            'id' => 11,
            'actif' => 0
        )), 'post');
        $this->log('Done');
    }

    /**
     * Returns true if the deleted data doesn't exist.
     * This means the script has already been run on this instance.
     */
    function already_run() {
        $r = xModel::load('commission_fonction', array(
            'id' => array(11)
        ))->get();
        return !$r;
    }
}

new iafbmIssue206();