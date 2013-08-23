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
        'date_biblio_demandee' => 'date_biblio_demandee',
        'date_biblio_recue' => 'date_biblio_recue',
        'date_relance' => 'date_relance',
        'date_rapport_recu' => 'date_rapport_recu',
        'date_transmis_evaluateur' => 'date_transmis_evaluateur',
        'date_entretien' => 'date_entretien',
        'date_accuse_lettre' => 'date_accuse_lettre',
        'date_accuse_email' => 'date_accuse_email',
        'commentaire' => 'commentaire'
    );

    var $primary = array('id');

    var $joins = array(
        'activite' => 'LEFT JOIN activites ON (evaluations.activite_id = activites.id)',
        'activite_nom' => 'LEFT JOIN activites_noms ON (activites.activite_nom_id = activites_noms.id)',
        'evaluation_type' => 'LEFT JOIN evaluations_types ON (evaluations.evaluation_type_id = evaluations_types.id)',
        'personne' => 'LEFT JOIN personnes ON (evaluations.personne_id = personnes.id)',
        'section' => 'LEFT JOIN sections ON (activites.section_id = sections.id)',
    );

    var $join = array('activite', 'activite_nom', 'evaluation_type', 'personne', 'section');
    
    
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
        'evaluation_evaluation' => 'evaluation_id',
        'evaluation_cdir' => 'evaluation_id',
        'evaluation_contrat' => 'evaluation_id',
        'activite' => 'evaluation_id',
        'evaluation_type' => array('evaluation_type_id' => 'id'),
        'personne' => array('personne_id' => 'id')
    );

}
