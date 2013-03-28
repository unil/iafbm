<?php

class EvaluationRapportModel extends iaModelMysql {
    
    var $table = 'evaluations_rapports';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'termine' => 'termine',
        'evaluation_id' => 'evaluation_id',
        'date_biblio_demandee' => 'date_biblio_demandee',
        'date_biblio_recue' => 'date_biblio_recue',
        'date_relance' => 'date_relance',
        'date_rapport_recu' => 'date_rapport_recu',
        'date_transmis_evaluateur' => 'date_transmis_evaluateur',
        'date_entretien' => 'date_entretien',
        'commentaire' => 'commentaire'
    );

    var $primary = array('id');

    var $joins = array(
        'evaluation' => 'JOIN evaluations ON (evaluations_rapports.evaluation_id = evaluations.id)'
        
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
