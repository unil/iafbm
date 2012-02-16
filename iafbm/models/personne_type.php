<?php

class PersonneTypeModel extends iaModelMysql {

    var $table = 'personnes_types';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'nom' => 'nom'
    );

    var $order_by = array('nom');

    var $primary = array('id');
}
