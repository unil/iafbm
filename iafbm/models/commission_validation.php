<?php

/**
 * @package iafbm
 * @subpackage model
 */
class CommissionValidationModel extends iaModelMysql {

    var $table = 'commissions_validations';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'commission_id' => 'commission_id',
        'termine' => 'termine',
        'decanat_date' => 'decanat_date',
        'decanat_etat' => 'decanat_etat',
        'decanat_commentaire' => 'decanat_commentaire',
        'dg_date' => 'dg_date',
        'dg_commentaire' => 'dg_commentaire',
        'cf_date' => 'cf_date',
        'cf_etat' => 'cf_etat',
        'cf_commentaire' => 'cf_commentaire',
        'cdir_date' => 'cdir_date',
        'cdir_etat' => 'cdir_etat',
        'cdir_commentaire' => 'cdir_commentaire',
        'reception_rapport' => 'reception_rapport',
        'envoi_proposition_nomination' => 'envoi_proposition_nomination',
        'commentaire' => 'commentaire'
    );

    var $primary = array('id');

    var $validation = array(
        'commission_id' => 'mandatory'
    );

    // Self-documentation
    var $description = 'phase de validation des commissions';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'commission_id' => 'identifiant de la commission',
        'termine' => 'phase terminée',
        'decanat_date' => 'date de validation par le Décanat',
        'decanat_etat' => 'identifiant de l\'état de validation par le Décanat',
        'decanat_commentaire' => 'commentaire de validation par le Décanat',
        'dg_date' => 'date du commentaire DG-CHUV',
        'dg_commentaire' => 'commentaire DG-CHUV',
        'cf_date' => 'date validation par le CF',
        'cf_etat' => 'identifiant de l\'état de validation par le CF',
        'cf_commentaire' => 'commentaire de validation par le CF',
        'cdir_date' => 'date de validation par le CDir',
        'cdir_etat' => 'identifiant de l\'état de validation par le CDir',
        'cdir_commentaire' => 'commentaire de validation par le CDir',
        'reception_rapport' => 'date de réception du rapport',
        'envoi_proposition_nomination' => 'date d\'envoi de la proposition de nomination',
        'commentaire' => 'commentaire'
    );
}
