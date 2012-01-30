<?php

class CommissionTravailEvenementTypeModel extends iaModelMysql {

    var $table = 'commissions_travails_evenements_types';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'created' => 'created',
        'modified' => 'modified',
        'nom' => 'nom'
    );

    var $primary = array('id');

    var $validation = array(
    );
}
