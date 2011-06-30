<?php

class AdresseTypeModel extends xModelMysql {

    var $table = 'adresses_types';

    var $mapping = array(
        'id' => 'id',
        'nom' => 'nom'
    );

    var $order_by = array('id');

    var $primary = array('id');
}
