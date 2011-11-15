<?php

class CandidatFormationModel extends iaModelMysql {

    var $table = 'candidats_formations';

    var $mapping = array(
        'id' => 'id',
        'created' => 'created',
        'modified' => 'modified',
        'actif' => 'actif',
        'candidat_id' => 'candidat_id',
        'formation_id' => 'formation_id',
        'lieu_these' => 'lieu_these',
        'date_these' => 'date_these',
        'commentaire' => 'commentaire'
    );

    var $primary = array('id');

    var $validation = array(
        'candidat_id' => 'mandatory',
        'formation_id' => 'mandatory'
    );
}
