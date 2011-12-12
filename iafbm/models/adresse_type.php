<?php

class AdresseTypeModel extends iaModelMysql {

    var $table = 'adresses_types';

    var $versioning = false;

    var $mapping = array(
        'id' => 'id',
        'nom' => 'nom'
    );

    var $order_by = array('id');

    var $primary = array('id');
}
