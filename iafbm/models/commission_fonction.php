<?php

class CommissionFonctionModel extends iaModelMysql {

    var $table = 'commissions_fonctions';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'nom' => 'nom',
        'description' => 'description',
        'position' => 'position'
    );

    var $primary = array('id');

    var $order_by = array('nom');

    var $validation = array(
        'nom' => array(
            'mandatory'
        )
    );
}
