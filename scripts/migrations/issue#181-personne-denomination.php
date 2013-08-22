<?php

require_once(dirname(__file__).'/../Script.php');

/**
 * @package scripts-migration
 */
class iafbmIssue181 extends iafbmScript {

    function run() {
        // Single run test
        if ($this->already_run()) {
            throw new Exception('This script has already run');
        }
        //
        $t = new xTransaction();
        $t->start();
        $this->update_fields($t);
        $this->update_records($t);
        $t->end();
    }

    function update_fields(xTransaction $t) {
        $statements = array(
            'ALTER TABLE iafbm.personnes_denominations ADD COLUMN nom_masculin VARCHAR(255) NOT NULL AFTER nom',
            'ALTER TABLE iafbm.personnes_denominations ADD COLUMN nom_feminin VARCHAR(255) NOT NULL AFTER nom_masculin',
            'ALTER TABLE iafbm.personnes_denominations ADD COLUMN abreviation VARCHAR(255) NOT NULL AFTER nom_feminin',
            'ALTER TABLE iafbm.personnes_denominations ADD COLUMN abreviation_masculin VARCHAR(255) NOT NULL AFTER abreviation',
            'ALTER TABLE iafbm.personnes_denominations ADD COLUMN abreviation_feminin VARCHAR(255) NOT NULL AFTER abreviation_masculin',
            'ALTER TABLE iafbm.personnes_denominations ADD COLUMN poids TINYINT UNSIGNED NOT NULL AFTER abreviation_feminin'
        );
        foreach ($statements as $statement) $t->execute_sql($statement);
    }

    function update_records(xTransaction $t) {
        $post = array(
            xModel::load('personne_denomination', array(
                'id' => 1,
                'nom' => 'Professeur(e)',
                'nom_masculin' => 'Professeur',
                'nom_feminin' => 'Professeure',
                'abreviation' => 'Prof.',
                'abreviation_masculin' => 'Prof.',
                'abreviation_feminin' => 'Prof.',
                'poids' => '5'
            )),
            xModel::load('personne_denomination', array(
                'id' => 2,
                'nom' => 'Docteur(e)',
                'nom_masculin' => 'Docteur',
                'nom_feminin' => 'Docteure',
                'abreviation' => 'Dr(e)',
                'abreviation_masculin' => 'Dr',
                'abreviation_feminin' => 'Dre',
                'poids' => '3'
            ))
        );
        $put = array(
            xModel::load('personne_denomination', array(
                'id' => 3,
                'nom' => 'Madame/Monsieur',
                'nom_masculin' => 'Monsieur',
                'nom_feminin' => 'Madame',
                'abreviation' => 'M/me',
                'abreviation_masculin' => 'M.',
                'abreviation_feminin' => 'Mme',
                'poids' => '1'
            )),
            xModel::load('personne_denomination', array(
                'id' => 4,
                'nom' => 'Docteur(e) en sciences',
                'nom_masculin' => 'Docteur en sciences',
                'nom_feminin' => 'Docteure en sciences',
                'abreviation' => 'Dr(e) Sc.',
                'abreviation_masculin' => 'Dr Sc.',
                'abreviation_feminin' => 'Dre Sc.',
                'poids' => '4'
            )),
            xModel::load('personne_denomination', array(
                'id' => 5,
                'nom' => 'Maître',
                'nom_masculin' => 'Maître',
                'nom_feminin' => 'Maître',
                'abreviation' => 'Me',
                'abreviation_masculin' => 'Me',
                'abreviation_feminin' => 'Me',
                'poids' => '2'
            )),
            xModel::load('personne_denomination', array(
                'id' => 6,
                'nom' => 'Doyen(ne)',
                'nom_masculin' => 'Doyen',
                'nom_feminin' => 'Doyenne',
                'abreviation' => 'Doyen(ne)',
                'abreviation_masculin' => 'Doyen',
                'abreviation_feminin' => 'Doyenne',
                'poids' => '7'
            )),
            xModel::load('personne_denomination', array(
                'id' => 7,
                'nom' => 'vice-Doyen(ne)',
                'nom_masculin' => 'vice-Doyen',
                'nom_feminin' => 'vice-Doyenne',
                'abreviation' => 'vice-Doyen(ne)',
                'abreviation_masculin' => 'vice-Doyen',
                'abreviation_feminin' => 'vice-Doyenne',
                'poids' => '6'
            )),
        );
        //
        foreach ($post as $model) $t->execute($model, 'post');
        foreach ($put as $model) $t->execute($model, 'put');
    }

    /**
     * Returns true if the fields to create already exist.
     * This means the script has already been run on this instance.
     */
    function already_run() {
        $fields = array('nom_masculin', 'nom_feminin', 'abreviation', 'abreviation_masculin', 'abreviation_feminin', 'poids');
        $r = xModel::q('DESCRIBE personnes_denominations');
        while ($row = mysql_fetch_assoc($r)) {
            $result[] = $row['Field'];
        }
        return !!array_intersect($result, $fields);
    }
}

new iafbmIssue181();