<?php

class EvaluationContratModel extends iaModelMysql {
    
    var $table = 'evaluations_contrats';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'termine' => 'termine',
        'evaluation_id' => 'evaluation_id',
        'copie_nouveau_contrat' => 'copie_nouveau_contrat',
        'commentaire' => 'commentaire'
    );

    var $primary = array('id');

    var $joins = array(
        'evaluation' => 'JOIN evaluations ON (evaluations_contrats.evaluation_id = evaluations.id)',        
    );

    var $join = array('evaluation');
    
    var $validation = array(
        'evaluation_id' => array('mandatory')
    );
    
    var $archive_foreign_models = array(
    );
}
