<?php

require_once(dirname(__file__).'/../Script.php');

class iafbmIssue191a extends iafbmScript {

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

    /**
     * Sets defaut=true for each personne with one adresse/telephone/email.
     */
    function update_records(xTransaction $t) {
        // Processes personnes_adresses
        $this->log('Processing table personnes_telephones');
        $r = xModel::q('
            select id, count(personne_id) as count from personnes_adresses
            group by personne_id
            having count = 1
        ');
        $count = mysql_num_rows($r);
        $this->log("Setting default for $count items", 1);
        while ($row = mysql_fetch_assoc($r)) {
            $m = xModel::load('personne_adresse', array(
                'id' => $row['id'],
                'defaut' => true
            ));
            $t->execute($m, 'post');
        }
        // Processes personnes_emails
        $this->log('Processing table personnes_emails');
        $r = xModel::q('
            select id, count(personne_id) as count from personnes_emails
            group by personne_id
            having count = 1
        ');
        $count = mysql_num_rows($r);
        $this->log("Setting default for $count items", 1);
        while ($row = mysql_fetch_assoc($r)) {
            $m = xModel::load('personne_email', array(
                'id' => $row['id'],
                'defaut' => true
            ));
            $t->execute($m, 'post');
        }
        // Processes personnes_telephones
        $this->log('Processing table personnes_telephones');
        $r = xModel::q('
            select id, count(personne_id) as count from personnes_telephones
            group by personne_id
            having count = 1
        ');
        $count = mysql_num_rows($r);
        $this->log("Setting default for $count items", 1);
        while ($row = mysql_fetch_assoc($r)) {
            $m = xModel::load('personne_telephone', array(
                'id' => $row['id'],
                'defaut' => true
            ));
            $t->execute($m, 'post');
        }
    }

    /**
     * Returns true if the fields to create already exist.
     * This means the script has already been run on this instance.
     */
    function already_run() {
        return false;
    }
}

new iafbmIssue191a();