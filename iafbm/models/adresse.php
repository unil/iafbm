<?php

class AdresseModel extends iaModelMysql {

    var $table = 'adresses';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'adresse_type_id' => 'adresse_type_id',
        'rue' => 'rue',
        'npa' => 'npa',
        'lieu' => 'lieu',
        'pays_id' => 'pays_id'
    );

    var $primary = array('id');

    var $joins = array(
        'adresse_type' => 'LEFT JOIN adresses_types ON (adresses.adresse_type_id = adresses_types.id)',
        'pays' => 'LEFT JOIN pays ON (adresses.pays_id = pays.id)'
    );

    var $validation = array(
        'adresse_type_id' => 'mandatory'
    );

    var $archive_foreign_models = array(
        'adresse_type' => array('adresse_type_id' => 'id')
    );

    // Self-documentation
    var $description = 'adresses';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'adresse_type_id' => 'identifiant interne du type d\'adresse',
        'rue' => 'rue',
        'npa' => 'code postal',
        'lieu' => 'lieu',
        'pays_id' => 'identifiant interne du pays'
    );
}
