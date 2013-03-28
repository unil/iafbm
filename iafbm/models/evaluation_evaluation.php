<?php

class EvaluationEvaluationModel extends iaModelMysql {
    
    var $table = 'evaluations_evaluations';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'termine' => 'termine',
        'evaluation_id' => 'evaluation_id',
        'date_rapport_evaluation' => 'date_rapport_evaluation',
        'preavis_evaluateur_id' => 'preavis_evaluateur_id',
        'preavis_decanat_id' => 'preavis_decanat_id',
        'date_liste_transmise' => 'date_liste_transmise',
        'date_dossier_transmis' => 'date_dossier_transmis',
        'commentaire' => 'commentaire'
    );

    var $primary = array('id');

    var $joins = array(
        'evaluation' => 'JOIN evaluations ON (evaluations_evaluations.evaluation_id = evaluations.id)',        
    );

    //var $join = array('evaluation');
    
    var $validation = array(
        'id' => array('mandatory'),
        'actif' => array('mandatory'),
        'termine' => array('mandatory'),
        'evaluation_id' => array('mandatory')
    );
    
    //TODO: Archivable infos 
}
