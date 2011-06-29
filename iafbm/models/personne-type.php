<?php

class PersonneTypeModel extends xModelMysql {

    var $table = 'personnes_types';

    var $mapping = array(
        'id' => 'id',
        'nom' => 'nom'
    );

    var $order_by = array('nom');

    var $primary = array('id');
}
