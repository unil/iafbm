<?php

class CommissionTravailEvenementModel extends iaModelMysql {

    var $table = 'commissions_travails_evenements';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'commission_id' => 'commission_id',
        'commission_travail_evenement_type_id' => 'commission_travail_evenement_type_id',
        'date' => 'date',
        'proces_verbal' => 'proces_verbal',
        'duree' => 'duree'
    );

    var $primary = array('id');

    var $joins = array(
        'commission_travail_evenement_type' => 'LEFT JOIN commissions_travails_evenements_types ON (commissions_travails_evenements.commission_travail_evenement_type_id = commissions_travails_evenements_types.id)',
    );

    var $validation = array(
    );

    var $archive_foreign_models = array(
        'commission_travail_evenement_type' => array('commission_travail_evenement_type_id' => 'id')
    );

    // Self-documentation
    var $description = 'événements de la phase de travail des commissions';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'commission_id' => 'identifiant de la commission',
        'commission_travail_evenement_type_id' => 'identifiant du type d\'événement',
        'date' => 'date de l\'événement',
        'proces_verbal' => 'procès verbal reçu',
        'duree' => 'duree en minutes'
    );
}
