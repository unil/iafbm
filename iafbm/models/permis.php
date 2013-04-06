<?php

class PermisModel extends iaModelMysql {

    var $table = 'permis';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'nom' => 'nom'
    );

    var $order_by = array('nom');

    var $primary = array('id');

    // Self-documentation
    var $description = 'catalogue des permis de séjour';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'nom' => 'nom du permis de séjour'
    );
}
