<?php

class CandidatFormationModel extends xModelMysql {

    var $table = 'candidats_formations';

    var $mapping = array(
        'id' => 'id',
        'created' => 'created',
        'modified' => 'modified',
        'actif' => 'actif',
        'candidat_id' => 'candidat_id',
        'candidat-formation-type_id' => 'candidat_formation_type_id',
        'date_these' => 'date_these',
        'lieu_these' => 'lieu_these'
    );

    var $primary = array('id');

    var $validation = array(
        'candidat_id' => 'mandatory',
        'candidat-formation-type_id' => 'mandatory'
    );
}
