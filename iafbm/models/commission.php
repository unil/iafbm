<?php

class CommissionModel extends xModelMysql {

    var $table = 'commissions';

    var $mapping = array(
        'id' => 'id',
        'commission-type_id' => 'commission_type_id',
        'nom' => 'nom',
        'description' => 'description',
        'actif' => 'actif',
        'created' => 'created',
        'modified' => 'modified'
    );

    var $primary = array('id');

    var $joins = array(
        'commission-type' => 'LEFT JOIN commissions_types ON (commissions.commission_type_id = commissions_types.id)'
    );

    var $join = 'commission-type';

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
}
