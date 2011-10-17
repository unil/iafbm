<?php

class PersonneEmailModel extends iaModelMysql {

    var $table = 'personnes_emails';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'created' => 'created',
        'modified' => 'modified',
        'personne_id' => 'personne_id',
        'adresse-type_id' => 'adresse_type_id',
        'email' => 'email',
        'defaut' => 'defaut'
    );

    var $primary = array('id');

    var $validation = array(
        'personne_id' => 'mandatory',
        'email' => 'email'
    );
}