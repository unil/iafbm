<?php

class DepartementModel extends iaModelMysql {

    var $table = 'departements';

    var $mapping = array(
        'id' => 'id',
        'nom' => 'nom'
    );

    var $primary = array('id');

    var $validation = array(
        'nom' => 'mandatory'
    );
}
