<?php

class CommissionFonctionModel extends iaModelMysql {

    var $table = 'commissions_fonctions';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'created' => 'created',
        'modified' => 'modified',
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
