<?php

class CantonModel extends xModelMysql {

    var $table = 'cantons';

    var $mapping = array(
        'id' => 'id',
        'code' => 'code',
        'nom' => 'nom',
    );

    var $primary = array('id');
}
