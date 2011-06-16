<?php

class CommissionTravailEvenementTypeModel extends xModelMysql {

    var $table = 'commissions_travails_evenements_types';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'nom' => 'nom',
    );

    var $primary = array('id');

    var $validation = array(
    );
}
