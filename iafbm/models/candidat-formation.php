<?php

class CandidatFormationModel extends xModelMysql {

    var $table = 'candidats_formations';

    var $mapping = array(
        'id' => 'id',
        'created' => 'created',
        'modified' => 'modified',
        'actif' => 'actif',
        'candidat_id' => 'candidat_id',
        'formation_id' => 'formation_id',
        'date_these' => 'date_these',
        'lieu_these' => 'lieu_these'
    );

    var $primary = array('id');

    var $validation = array(
        'candidat_id' => 'mandatory',
        'formation-titre_id' => 'mandatory'
    );
}
