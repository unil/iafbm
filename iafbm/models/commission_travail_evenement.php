<?php

class CommissionTravailEvenementModel extends iaModelMysql {

    var $table = 'commissions_travails_evenements';

    var $mapping = array(
        'id' => 'id',
        'created' => 'created',
        'modified' => 'modified',
        'actif' => 'actif',
        'commission_id' => 'commission_id',
        'commission_travail_evenement_type_id' => 'commission_travail_evenement_type_id',
        'date' => 'date',
        'proces_verbal' => 'proces_verbal'
    );

    var $primary = array('id');

    var $validation = array(
    );
}
