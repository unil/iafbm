<?php

class CommissionModel extends iaModelMysql {

    var $table = 'commissions';

    var $mapping = array(
        'id' => 'id',
        'commission_type_id' => 'commission_type_id',
        'commission_etat_id' => 'commission_etat_id',
        'section_id' => 'section_id',
        'termine' => 'termine',
        'nom' => 'nom',
        'institut' => 'institut',
        'commentaire' => 'commentaire',
        'actif' => 'actif',
    );

    var $primary = array('id');

    var $joins = array(
        'commission_type' => 'LEFT JOIN commissions_types ON (commissions.commission_type_id = commissions_types.id)',
        'commission_etat' => 'LEFT JOIN commissions_etats ON (commissions.commission_etat_id = commissions_etats.id)',
        'section' => 'LEFT JOIN sections ON (commissions.section_id = sections.id)',
        'commission_membre' => 'LEFT JOIN commissions_membres ON (commissions.id = commissions_membres.commission_id)',
        'commission_fonction' => 'LEFT JOIN commissions_fonctions ON (commissions_membres.commission_fonction_id = commissions_fonctions.id)'
    );

    var $join = array('commission_type', 'section');

    var $validation = array(
        'nom' => array('mandatory')
    );

    var $archivable = true;

    var $archive_foreign_models = array(
         'commission_creation' => 'commission_id',
         'commission_membre' => 'commission_id',
         'commission_candidat_commentaire' => 'commission_id',
         'candidat' => 'commission_id',
         'commission_travail' => 'commission_id',
         'commission_travail_evenement' => 'commission_id',
         'commission_validation' => 'commission_id',
         'commission_proposition_nomination' => 'commission_id',
         'commission_finalisation' => 'commission_id',
         'commission_type' => array('commission_type_id' => 'id'),
         'commission_etat' => array('commission_etat_id' => 'id'),
         'section' => array('section_id' => 'id')
    );
}
