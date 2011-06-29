<?php

class TitreModel extends xModelMysql {

    var $table = 'titres';

    var $mapping = array(
        'id' => 'id',
        'abreviation' => 'abreviation',
        'nom' => 'nom'
    );

    var $order_by = array('id');

    var $primary = array('id');
}
