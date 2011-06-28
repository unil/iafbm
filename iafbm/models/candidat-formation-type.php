<?php

class CandidatFormationTypeModel extends xModelMysql {

    var $table = 'candidats_formations_types';

    var $mapping = array(
        'id' => 'id',
        'nom' => 'nom'
    );

    var $primary = array('id');

    var $validation = array(
        'nom' => 'mandatory'
    );
}
