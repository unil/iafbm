<?php

class CandidatFormationModel extends iaModelMysql {

    var $table = 'candidats_formations';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'candidat_id' => 'candidat_id',
        'formation_id' => 'formation_id',
        'date_these' => 'date_these',
        'lieu_these' => 'lieu_these',
        'commentaire' => 'commentaire'
    );

    var $primary = array('id');

    var $validation = array(
        'candidat_id' => 'mandatory',
        'formation_id' => 'mandatory'
    );

    var $joins = array(
        'formation' => 'LEFT JOIN formations ON (candidats_formations.formation_id = formations.id)'
    );

    var $join = array('formation');

    var $archive_foreign_models = array(
        'formation' => array('formation_id' => 'id')
    );
}
