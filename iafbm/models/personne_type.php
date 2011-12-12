<?php

class PersonneTypeModel extends iaModelMysql {

    var $table = 'personnes_types';

    var $versioning = false;

    var $mapping = array(
        'id' => 'id',
        'nom' => 'nom'
    );

    var $order_by = array('nom');

    var $primary = array('id');
}
