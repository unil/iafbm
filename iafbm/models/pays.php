<?php

class PaysModel extends iaModelMysql {

    var $table = 'pays';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'code' => 'code',
        'nom' => 'nom',
        'nom_en' => 'nom_en'
    );

    var $order_by = array('nom');

    var $primary = array('id');
}
