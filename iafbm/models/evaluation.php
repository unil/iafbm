<?php

class EvaluationModel extends iaModelMysql {

    var $table = 'evaluations';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'termine' => 'termine',
        'evaluation_type_id' => 'evaluation_type_id',
        'date_periode_debut' => 'date_periode_debut',
        'date_periode_fin' => 'date_periode_fin',
        'personne_id' => 'personne_id',
        'activite_id' => 'activite_id',
        'evaluation_etat_id' => 'evaluation_etat_id',
    );

    var $primary = array('id');

    var $joins = array(
        'activite' => 'LEFT JOIN activites ON (evaluations.activite_id = activites.id)',
        'activite_nom' => 'LEFT JOIN activites_noms ON (activites.activite_nom_id = activites_noms.id)',
        'evaluation_type' => 'LEFT JOIN evaluations_types ON (evaluations.evaluation_type_id = evaluations_types.id)',
        'personne' => 'LEFT JOIN personnes ON (evaluations.personne_id = personnes.id)',
        'section' => 'LEFT JOIN sections ON (activites.section_id = sections.id)',
        'evaluation_etat' => 'LEFT JOIN evaluations_etats ON (evaluations.evaluation_etat_id = evaluations_etats.id)',
    );

    var $join = array('activite', 'activite_nom', 'evaluation_type', 'personne', 'section', 'evaluation_etat');
    
    
    var $wheres = array(
        'query' => 'common/model/query',
    );
    
    var $validation = array(
        'evaluation_type_id' => array('mandatory'),
        'personne_id' => array('mandatory'),
        'activite_id' => array('mandatory'),
    );
    
    var $archivable = true;
    
    var $archive_foreign_models = array(
        'evaluation_apercu' => 'evaluation_id',
        'evaluation_rapport' => 'evaluation_id',
        'evaluation_evaluation' => 'evaluation_id',
        'evaluation_cdir' => 'evaluation_id',
        'evaluation_contrat' => 'evaluation_id',
        'activite' => 'evaluation_id',
        'evaluation_type' => array('evaluation_type_id' => 'id'),
        'personne' => array('personne_id' => 'id')
    );

}
