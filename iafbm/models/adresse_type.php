<?php

class AdresseTypeModel extends iaModelMysql {

    var $table = 'adresses_types';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'created' => 'created',
        'modified' => 'modified',
        'nom' => 'nom'
    );

    var $primary = array('id');

    var $order_by = array('id');
}
