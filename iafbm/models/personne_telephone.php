<?php

class PersonneTelephoneModel extends iaModelMysql {

    var $table = 'personnes_telephones';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'created' => 'created',
        'modified' => 'modified',
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
}