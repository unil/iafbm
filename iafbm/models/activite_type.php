<?php

class ActiviteTypeModel extends iaModelMysql {

    var $table = 'activites_types';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'nom' => 'nom'
    );

    var $primary = array('id');

    var $validation = array(
        'nom' => 'mandatory'
    );
}
