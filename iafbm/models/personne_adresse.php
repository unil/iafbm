<?php

class PersonneAdresseModel extends iaModelMysql {

    var $table = 'personnes_adresses';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
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
        'personne' => 'LEFT JOIN personnes ON (personnes_adresses.personne_id = personnes.id)',
        'adresse' => 'LEFT JOIN adresses ON (personnes_adresses.adresse_id = adresses.id)',
        'adresse_type' => 'LEFT JOIN adresses_types ON (adresses.adresse_type_id = adresses_types.id)',
        'pays' => 'LEFT JOIN pays ON (adresses.pays_id = pays.id)'
    );

    var $join = array('adresse');

    var $archive_foreign_models = array(
        'adresse' => array('adresse_id' => 'id')
    );
}
