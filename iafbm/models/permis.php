<?php

class PermisModel extends iaModelMysql {

    var $table = 'permis';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'created' => 'created',
        'modified' => 'modified',
        'nom' => 'nom'
    );

    var $order_by = array('nom');

    var $primary = array('id');
}
