<?php

class EvaluationRapportModel extends iaModelMysql {
    
    var $table = 'evaluations_rapport';

    var $mapping = array(
        'id' => 'id',
        'relance' => 'relance',
        'rapport_recu' => 'rapport_recu',
        'bibliometrie' => 'bibliometrie',
        'entretien' => 'entretien',
        'transmis_evaluateurs' => 'transmis_evaluateurs',
        'commentaire' => 'commentaire',
    );

    var $primary = array('id');

    var $joins = array(
        'personne_evaluation' => 'JOIN personnes_evaluations ON (personnes_evaluations.evaluation_id = evaluations_rapport.id)',
        'personne' => 'LEFT JOIN personnes ON (personnes_evaluations.personne_id = personnes.id)',
        
    );

    var $join = array('personne_evaluation', 'personne');
}
