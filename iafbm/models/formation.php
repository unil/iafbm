<?php

class FormationModel extends iaModelMysql {

    var $table = 'formations';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'abreviation' => 'abreviation'
    );

    var $primary = array('id');

    var $validation = array(
        'abreviation' => 'mandatory'
    );

    // Self-documentation
    var $description = 'catalogue des formations';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'abreviation' => 'abréviation de la formation'
    );
}