<?php

class EvaluationDecisionModel extends iaModelMysql {
    
    var $table = 'evaluations_decisions';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'decision' => 'decision',
        'commentaire' => 'commentaire'
    );

    var $primary = array('id');
    
    var $validation = array(
        'decision' => array('mandatory')
    );
    
    var $archive_foreign_models = array(
        
    );
}
