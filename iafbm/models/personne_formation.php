<?php

class PersonneFormationModel extends iaModelMysql {

    var $table = 'personnes_formations';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'personne_id' => 'personne_id',
        'formation_id' => 'formation_id',
        'date_these' => 'date_these',
        'lieu_these' => 'lieu_these',
        'commentaire' => 'commentaire'
    );

    var $primary = array('id');

    var $validation = array(
        'personne_id' => 'mandatory',
        'formation_id' => 'mandatory'
    );

    var $joins = array(
        'formation' => 'LEFT JOIN formations ON (personnes_formations.formation_id = formations.id)'
    );

    var $archive_foreign_models = array(
        'formation' => array('formation_id' => 'id')
    );

    // Self-documentation
    var $description = 'formation des personnes';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'personne_id' => 'identifiant de la personne',
        'formation_id' => 'formation de la personne',
        'date_these' => 'date de thèse',
        'lieu_these' => 'lieu de la thèse',
        'commentaire' => 'commentaire'
    );
}
