<?php

class CommissionModel extends xModelMysql {

    var $table = 'commissions';

    var $mapping = array(
        'id' => 'id',
        'commissiontype-id' => 'commission_type_id',
        'nom' => 'nom',
        'description' => 'description',
        'actif' => 'actif',
        'created' => 'created',
        'modified' => 'modified'
    );

    var $validation = array(
        'nom' => array(
            'mandatory'
        ),
/*
        'end' => array(
            'mandatory',
            'datetime'
        ),
        'zip' => array(
            'mandatory',
            'integer',
            'minlength' => array('length'=>4),
            'maxlength' => array('length'=>4)
        ),
        'location' => array(
            'mandatory'
        ),
        'distance' => array(
            'mandatory',
            'integer',
            'minvalue' => array('length'=>0)
        ),
        'profile' => array(
            'mandatory',
            'integer'
        ),
*/
    );

    var $primary = array('id');

    var $joins = array(
        //'supplier' => 'LEFT JOIN profile_supplier ON (availability.fk_profile = profile_supplier.id)'
    );
}
