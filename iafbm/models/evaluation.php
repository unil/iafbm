<?php

class EvaluationModel extends iaModelMysql {

    var $table = 'evaluations';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'evaluation_type_id' => 'evaluation_type_id',
        'date_periode_debut' => 'date_periode_debut',
        'date_periode_fin' => 'date_periode_fin',
        'personne_id' => 'personne_id',
        'activite_id' => 'activite_id',
    );

    var $primary = array('id');

    var $joins = array(
        'activite' => 'LEFT JOIN activites ON (evaluations.activite_id = activites.id)',
        'evaluation_type' => 'LEFT JOIN evaluations_types ON (evaluations.evaluation_type_id = evaluations_types.id)'
    );

    var $join = array('activite', 'evaluation_type');

    var $validation = array(
        'id' => array('mandatory'),
        'actif' => array('mandatory'),
        'evaluation_type_id' => array('mandatory'),
        'personne_id' => array('mandatory'),
        'activite_id' => array('mandatory')
    );
    
    //TODO: Archivable infos 
}
