<?php

class PersonneFormationModel extends xModelMysql {

    var $table = 'personnes_formations';

    var $mapping = array(
        'id' => 'id',
        'created' => 'created',
        'modified' => 'modified',
        'actif' => 'actif',
        'personne_id' => 'personne_id',
        'formation_id' => 'formation_id',
        'date_these' => 'date_these',
        'lieu_these' => 'lieu_these'
    );

    var $primary = array('id');

    var $validation = array(
        'personne_id' => 'mandatory',
        'formation_id' => 'mandatory'
    );
}
