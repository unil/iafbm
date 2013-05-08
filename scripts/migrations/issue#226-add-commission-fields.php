<?php

require_once(dirname(__file__).'/../Script.php');

class iafbmIssue226 extends iafbmScript {

    function run() {
        // Single run test
        if ($this->already_run()) {
            throw new Exception('This script has already run');
        }
        //
        $this->create_fields();
    }

    function create_fields() {
        // Collects foreign keys contstraints names
        $keys = array();
        foreach (xModel::load('pays')->query(
            "select CONSTRAINT_NAME, COLUMN_NAME
            from INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            where
            REFERENCED_TABLE_NAME = 'commissions_validations_etats'"
        ) as $key) {
            $keys[$key['COLUMN_NAME']] = $key['CONSTRAINT_NAME'];
        }
        // Creates and executes tables modification queries
        $statements = array(
            // Updates and adds commissions_creations preavis fields
            'ALTER TABLE iafbm.commissions_creations CHANGE date_preavis date_preavis_cpa DATE DEFAULT NULL',
            'ALTER TABLE iafbm.commissions_creations ADD COLUMN date_preavis_ccp DATE DEFAULT NULL AFTER date_decision',
            'ALTER TABLE iafbm.commissions_creations ADD COLUMN date_preavis_decanat DATE DEFAULT NULL AFTER date_decision',
            // Adds commissions_candidats date_cloture field
            'ALTER TABLE iafbm.commissions_candidats_commentaires ADD COLUMN date_cloture DATE DEFAULT NULL AFTER commission_id',
            // Adds commissions_travails aucun_candidat, delai_envoi_rapport fields
            'ALTER TABLE iafbm.commissions_travails ADD COLUMN aucun_candidat BOOLEAN NOT NULL DEFAULT false AFTER termine',
            'ALTER TABLE iafbm.commissions_travails ADD COLUMN delai_envoi_rapport DATE DEFAULT NULL AFTER loco_tertio',
            // Adds commissions_validations validations cdir_nomination_* fields
            'ALTER TABLE iafbm.commissions_validations ADD COLUMN cdir_nomination_date DATE DEFAULT NULL AFTER cdir_commentaire',
            'ALTER TABLE iafbm.commissions_validations ADD COLUMN cdir_nomination_etat INT DEFAULT 1 AFTER cdir_nomination_date',
            'ALTER TABLE iafbm.commissions_validations ADD COLUMN cdir_nomination_commentaire TEXT AFTER cdir_nomination_etat',
            'ALTER TABLE iafbm.commissions_validations ADD FOREIGN KEY (cdir_nomination_etat) REFERENCES commissions_validations_etats(id)',
            // Updates commissions_validations fields names
            "ALTER TABLE iafbm.commissions_validations DROP FOREIGN KEY {$keys['decanat_etat']}",
            'ALTER TABLE iafbm.commissions_validations CHANGE decanat_date decanat_validation_date DATE DEFAULT NULL',
            'ALTER TABLE iafbm.commissions_validations CHANGE decanat_etat decanat_validation_etat INT DEFAULT 1',
            'ALTER TABLE iafbm.commissions_validations CHANGE decanat_commentaire decanat_validation_commentaire TEXT',
            'ALTER TABLE iafbm.commissions_validations ADD FOREIGN KEY (decanat_validation_etat) REFERENCES commissions_validations_etats(id)',
            "ALTER TABLE iafbm.commissions_validations DROP FOREIGN KEY {$keys['cf_etat']}",
            'ALTER TABLE iafbm.commissions_validations CHANGE cf_date cf_validation_date DATE DEFAULT NULL',
            'ALTER TABLE iafbm.commissions_validations CHANGE cf_etat cf_validation_etat INT DEFAULT 1',
            'ALTER TABLE iafbm.commissions_validations CHANGE cf_commentaire cf_validation_commentaire TEXT',
            'ALTER TABLE iafbm.commissions_validations ADD FOREIGN KEY (cf_validation_etat) REFERENCES commissions_validations_etats(id)',
            "ALTER TABLE iafbm.commissions_validations DROP FOREIGN KEY {$keys['cdir_etat']}",
            'ALTER TABLE iafbm.commissions_validations CHANGE cdir_date cdir_validation_date DATE DEFAULT NULL',
            'ALTER TABLE iafbm.commissions_validations CHANGE cdir_etat cdir_validation_etat INT DEFAULT 1',
            'ALTER TABLE iafbm.commissions_validations CHANGE cdir_commentaire cdir_validation_commentaire TEXT',
            'ALTER TABLE iafbm.commissions_validations ADD FOREIGN KEY (cdir_validation_etat) REFERENCES commissions_validations_etats(id)',
            'ALTER TABLE iafbm.commissions_validations CHANGE dg_date dg_commentaire_date DATE DEFAULT NULL',
            'ALTER TABLE iafbm.commissions_validations CHANGE dg_commentaire dg_commentaire_commentaire TEXT',
        );
        // Updates versioning and archive tables entries (model_name and table_name)
        $fields = array(
            'preavis' => 'preavis_cpa',
            'decanat_date' => 'decanat_validation_date',
            'decanat_etat' => 'decanat_validation_etat',
            'decanat_commentaire' => 'decanat_validation_commentaire',
            'cf_date' => 'cf_validation_date',
            'cf_etat' => 'cf_validation_etat',
            'cf_commentaire' => 'cf_validation_commentaire',
            'cdir_date' => 'cdir_validation_date',
            'cdir_etat' => 'cdir_validation_etat',
            'cdir_commentaire' => 'cdir_validation_commentaire',
            'dg_date' => 'dg_commentaire_date',
            'dg_commentaire' => 'dg_commentaire_commentaire',
        );
        foreach ($fields as $old => $new) {
            // Versioning
            $statements[] = "UPDATE versions_data SET field_name = '{$new}' WHERE field_name = '{$old}'";
            // Archives
            $statements[] = "UPDATE archives_data SET model_field_name = '{$new}' WHERE model_field_name = '{$old}'";
        }
        // Actual statements processing
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
        $fields = array('dg_commentaire_commentaire');  // Last field added
        $r = xModel::q('DESCRIBE commissions_validations');
        while ($row = mysql_fetch_assoc($r)) {
            $result[] = $row['Field'];
        }
        return !!array_intersect($result, $fields);
    }
}

new iafbmIssue226();