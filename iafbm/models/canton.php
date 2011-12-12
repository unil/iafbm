<?php

class CantonModel extends iaModelMysql {

    var $table = 'cantons';

    var $versioning = false;

    var $mapping = array(
        'id' => 'id',
        'code' => 'code',
        'nom' => 'nom',
    );

    var $primary = array('id');

    var $order_by = array('nom');
}
