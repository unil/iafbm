<?php

class AdresseModel extends iaModelMysql {

    var $table = 'adresses';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'created' => 'created',
        'modified' => 'modified',
        'adresse-type_id' => 'adresse_type_id',
        'rue' => 'rue',
        'npa' => 'npa',
        'lieu' => 'lieu',
        'pays_id' => 'pays_id',
        'telephone_countrycode' => 'telephone_countrycode',
        'telephone' => 'telephone',
        'defaut' => 'defaut'
    );

    var $primary = array('id');

    var $joins = array(
        'pays' => 'LEFT JOIN pays ON (personnes.pays_id = pays.id)'
    );

    var $validation = array();
}
