<?php

class GenreModel extends iaModelMysql {

    var $table = 'genres';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'nom' => 'nom',
        'initiale' => 'initiale',
        'intitule' => 'intitule',
        'intitule_abreviation' => 'intitule_abreviation'
    );

    var $order_by = array('id');

    var $primary = array('id');

    // Self-documentation
    var $description = 'catalogue des genres';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'nom' => 'nom du genre',
        'initiale' => 'initiale du genre',
        'intitule' => 'intitulé du genre',
        'intitule_abreviation' => 'abreviation de l\'intitulé'
    );
}
