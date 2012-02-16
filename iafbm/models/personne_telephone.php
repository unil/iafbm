<?php

class PersonneTelephoneModel extends iaModelMysql {

    var $table = 'personnes_telephones';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'personne_id' => 'personne_id',
        'adresse_type_id' => 'adresse_type_id',
        'countrycode' => 'countrycode',
        'telephone' => 'telephone',
        'defaut' => 'defaut'
    );

    var $primary = array('id');

    var $validation = array(
        'personne_id' => 'mandatory',
        'email' => 'email'
    );

    var $archive_foreign_models = array(
        'adresse_type' => array('adresse_type_id' => 'id')
    );
}