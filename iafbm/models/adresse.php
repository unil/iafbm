<?php

class AdresseModel extends xModelMysql {

    var $table = 'adresses';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'created' => 'created',
        'modified' => 'modified',
        'adresse-type_id' => 'adresse_type_id',
        'adresse' => 'adresse',
        'npa' => 'npa',
        'lieu' => 'lieu',
        'pays_id' => 'pays_id',
        'telephone' => 'telephone'
    );

    var $primary = array('id');

    var $joins = array(
        'pays' => 'LEFT JOIN pays ON (personnes.pays_id = pays.id)'
    );

    var $validation = array(
        'adresse' => 'mandatory'
    );
}
