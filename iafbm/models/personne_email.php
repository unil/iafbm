<?php

class PersonneEmailModel extends iaModelMysql {

    var $table = 'personnes_emails';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'personne_id' => 'personne_id',
        'adresse_type_id' => 'adresse_type_id',
        'email' => 'email',
        'defaut' => 'defaut'
    );

    var $primary = array('id');

    var $validation = array(
        'personne_id' => 'mandatory',
        'email' => 'email'
    );

    var $archive_foreign_models = array(
        // FIXME: missing 'personne' relation here?
        'adresse_type' => array('adresse_type_id' => 'id')
    );
}