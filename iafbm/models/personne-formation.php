<?php

class PersonneFormationModel extends xModelMysql {

    var $table = 'personnes_formations';

    var $mapping = array(
        'id' => 'id',
        'created' => 'created',
        'modified' => 'modified',
        'actif' => 'actif',
        'personne_id' => 'personne_id',
        'formation-titre_id' => 'formation_titre_id',
        'date_these' => 'date_these',
        'lieu_these' => 'lieu_these'
    );

    var $primary = array('id');

    var $validation = array(
        'personne_id' => 'mandatory',
        'formation-titre_id' => 'mandatory'
    );
}
