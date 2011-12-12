<?php

class CommissionTravailEvenementTypeModel extends iaModelMysql {

    var $table = 'commissions_travails_evenements_types';

    var $versioning = false;

    var $mapping = array(
        'id' => 'id',
        'nom' => 'nom',
    );

    var $primary = array('id');

    var $validation = array(
    );
}
