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
        'evaluation_type' => 'LEFT JOIN evaluations_types ON (evaluations.evaluation_type_id = evaluations_types.id)',
        'personne' => 'LEFT JOIN personnes ON (evaluations.personne_id = personnes.id)'
    );

    var $join = array('activite', 'evaluation_type', 'personne');

    var $validation = array(
        'id' => array('mandatory'),
        'actif' => array('mandatory'),
        'evaluation_type_id' => array('mandatory'),
        'personne_id' => array('mandatory'),
        'activite_id' => array('mandatory')
    );
    
    var $archive_foreign_models = array(
        'evaluation_rapport' => 'evaluation_id',
        'evaluation_evaluation' => 'evaluation_id',
        'evaluation_cdir' => 'evaluation_id',
        'evaluation_contrat' => 'evaluation_id',
        'activite' => 'evaluation_id',
        'evaluation_type' => array('evaluation_type_id' => 'id'),
        'personne' => array('personne_id' => 'id')
    );

}
