<?php

class EvaluationEtatModel extends iaModelMysql {
    
    var $table = 'evaluations_etats';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'etat' => 'etat'
    );

    var $primary = array('id');
    
    var $validation = array(
        'etat' => array('mandatory')
    );
    
    var $archive_foreign_models = array(
        
    );
}
