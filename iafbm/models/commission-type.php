<?php

class CommissionTypeModel extends xModelMysql {

    var $table = 'commissions_types';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'created' => 'created',
        'modified' => 'modified',
        'nom' => 'nom',
        'racine' => 'racine',
    );

    var $primary = array('id');

    var $order_by = array('nom');

    var $validation = array(
        'nom' => array(
            'mandatory',
            'maxlength' => array('length'=>255)
        ),
        'racine' => array(
            'mandatory',
            'maxlength' => array('length'=>32)
        )
    );
}
