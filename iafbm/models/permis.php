<?php

class PermisModel extends iaModelMysql {

    var $table = 'permis';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'nom' => 'nom'
    );

    var $order_by = array('nom');

    var $primary = array('id');
}
