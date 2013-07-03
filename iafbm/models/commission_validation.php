<?php

class CommissionValidationModel extends iaModelMysql {

    var $table = 'commissions_validations';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'commission_id' => 'commission_id',
        'termine' => 'termine',
        'decanat_validation_date' => 'decanat_validation_date',
        'decanat_validation_etat' => 'decanat_validation_etat',
        'decanat_validation_commentaire' => 'decanat_validation_commentaire',
        'dg_commentaire_date' => 'dg_commentaire_date',
        'dg_commentaire_commentaire' => 'dg_commentaire_commentaire',
        'cf_validation_date' => 'cf_validation_date',
        'cf_validation_etat' => 'cf_validation_etat',
        'cf_validation_commentaire' => 'cf_validation_commentaire',
        'cdir_validation_date' => 'cdir_validation_date',
        'cdir_validation_etat' => 'cdir_validation_etat',
        'cdir_validation_commentaire' => 'cdir_validation_commentaire',
        'cdir_nomination_date' => 'cdir_nomination_date',
        'cdir_nomination_etat' => 'cdir_nomination_etat',
        'cdir_nomination_commentaire' => 'cdir_nomination_commentaire',
        'reception_rapport' => 'reception_rapport',
        'envoi_proposition_nomination' => 'envoi_proposition_nomination',
        'commentaire' => 'commentaire'
    );

    var $primary = array('id');

    var $validation = array(
        'commission_id' => 'mandatory'
    );
}
