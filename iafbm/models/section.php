<?php

class SectionModel extends xModelMysql {

    var $table = 'sections';

    var $mapping = array(
        'id' => 'id',
        'code' => 'code',
        'nom' => 'nom'
    );

    var $order_by = array('nom');

    var $primary = array('id');
}
