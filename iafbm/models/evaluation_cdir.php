<?php

class EvaluationCdirModel extends iaModelMysql {
    
    var $table = 'evaluations_cdirs';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'termine' => 'termine',
        'evaluation_id' => 'evaluation_id',
        'seance_cdir' => 'seance_cdir',
        'confirmation' => 'confirmation',
        'renouvellement' => 'renouvellement',
        'commentaire' => 'commentaire'
    );

    var $primary = array('id');

    var $joins = array(
        'evaluation' => 'JOIN evaluations ON (evaluations_cdirs.evaluation_id = evaluations.id)',        
    );

    var $join = array('evaluation');
    
    var $validation = array(
        'evaluation_id' => array('mandatory')
    );
    
    var $archive_foreign_models = array(
        
    );
}
