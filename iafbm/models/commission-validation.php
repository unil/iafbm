<?php

class CommissionValidationModel extends xModelMysql {

    var $table = 'commissions_validations';

    var $mapping = array(
        'id' => 'id',
        'created' => 'created',
        'modified' => 'modified',
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
}
