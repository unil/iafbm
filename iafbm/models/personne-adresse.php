<?php

class PersonneAdresseModel extends iaModelMysql {

    var $table = 'personnes_adresses';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'created' => 'created',
        'modified' => 'modified',
        'personne_id' => 'personne_id',
        'adresse_id' => 'adresse_id',
        'defaut' => 'defaut'
    );

    var $primary = array('id');

    var $validation = array(
        'adresse_id' => 'mandatory',
        'personne_id' => 'mandatory'
    );

    var $joins = array(
        'adresse' => 'LEFT JOIN adresses ON (personnes_adresses.adresse_id = adresses.id)',
        'personne' => 'LEFT JOIN personnes ON (personnes_adresses.personne_id = personnes.id)'
    );

    var $join = array('adresse');
}
