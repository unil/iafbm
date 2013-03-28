<?php

class EvaluationTypeModel extends iaModelMysql {

    var $table = 'evaluations_types';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'type' => 'type'
    );

    var $primary = array('id');

    var $validation = array(
        'id' => array('mandatory'),
        'actif' => array('mandatory'),
        'type' => array('mandatory'),
    );
    
    //TODO: Archivable infos 
}
