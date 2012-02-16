<?php

class CantonModel extends iaModelMysql {

    var $table = 'cantons';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'code' => 'code',
        'nom' => 'nom'
    );

    var $primary = array('id');

    var $order_by = array('nom');
}
