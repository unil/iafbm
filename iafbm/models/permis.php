<?php

class PermisModel extends iaModelMysql {

    var $table = 'permis';

    var $versioning = false;

    var $mapping = array(
        'id' => 'id',
        'nom' => 'nom',
    );

    var $order_by = array('nom');

    var $primary = array('id');
}
