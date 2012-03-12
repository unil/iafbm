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
}
