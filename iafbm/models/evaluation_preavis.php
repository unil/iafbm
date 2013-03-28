<?php

class EvaluationPreavisModel extends iaModelMysql {

    var $table = 'evaluations_preavis';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'preavis' => 'preavis'
    );

    var $primary = array('id');

    var $validation = array(
        'id' => array('mandatory'),
        'actif' => array('mandatory'),
        'preavis' => array('mandatory')
    );
    
    //TODO: Archivable infos 
}
