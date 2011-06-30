<?php

class PersonneAdresseModel extends xModelMysql {

    var $table = 'personnes_adresses';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'created' => 'created',
        'modified' => 'modified',
        'personne_id' => 'personne_id',
        'adresse_id' => 'adresse_id'
    );

    var $primary = array('id');

    var $validation = array(
        'adresse_id' => 'mandatory',
        'personne_id' => 'mandatory'
    );
}
