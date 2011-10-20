<?php

class PersonneFormationModel extends iaModelMysql {

    var $table = 'personnes_formations';

    var $mapping = array(
        'id' => 'id',
        'created' => 'created',
        'modified' => 'modified',
        'actif' => 'actif',
        'personne_id' => 'personne_id',
        'formation_id' => 'formation_id',
        'date_these' => 'date_these',
        'lieu_these' => 'lieu_these',
        'commentaire' => 'commentaire'
    );

    var $primary = array('id');

    var $joins = array(
        'formation' => 'LEFT JOIN formations ON (personnes_formations.formation_id = formations.id)'
    );

    var $join = 'formation';

    var $validation = array(
        'personne_id' => 'mandatory',
        'formation_id' => 'mandatory'
    );
}
