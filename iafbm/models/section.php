<?php

class SectionModel extends iaModelMysql {

    var $table = 'sections';

    var $versioning = false;

    var $mapping = array(
        'id' => 'id',
        'code' => 'code',
        'nom' => 'nom'
    );

    var $order_by = array('nom');

    var $primary = array('id');
}
