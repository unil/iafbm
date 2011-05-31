<?php

class CommissionEtatModel extends xModelMysql {

    var $table = 'commissions_etats';

    var $mapping = array(
        'id' => 'id',
        'nom' => 'nom',
        'description' => 'description'
    );

    var $primary = array('id');

    var $order_by = array('nom');

    var $validation = array(
        'nom' => array(
            'mandatory'
        )
    );
}
