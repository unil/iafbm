<?php

class CommissionMembreModel extends iaModelMysql {

    var $table = 'commissions_membres';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'personne_id' => 'personne_id',
        'commission_id' => 'commission_id',
        'commission_fonction_id' => 'commission_fonction_id',
        'activite_id' => 'activite_id',
        'departement_id' => 'departement_id'
    );

    var $primary = array('id');

    var $joins = array(
        'personne' => 'LEFT JOIN personnes ON (commissions_membres.personne_id = personnes.id)',
        'commission_fonction' => 'LEFT JOIN commissions_fonctions ON (commissions_membres.commission_fonction_id = commissions_fonctions.id)',
        'activite' => 'LEFT JOIN activites ON (commissions_membres.activite_id = activites.id)',
        'departement' => 'LEFT JOIN departements ON (commissions_membres.departement_id = departements.id)',
        'commission' => 'LEFT JOIN commissions ON (commissions_membres.commission_id = commissions.id)',
        'commission_type' => 'LEFT JOIN commissions_types ON (commissions.commission_type_id = commissions_types.id)',
        'commission_etat' => 'LEFT JOIN commissions_etats ON (commissions.commission_etat_id = commissions_etats.id)',
        'section' => 'LEFT JOIN sections ON (commissions.section_id = sections.id)'
    );

    var $join = array('personne', 'commission', 'commission_fonction', 'activite', 'departement');

    var $wheres = array(
        'query' => "{{personne_id}} = {personne_id} AND commissions_membres.actif = 1 AND (1=0 [OR {{*}} LIKE {*}])"
    );

    var $validation = array();

    var $archive_foreign_models = array(
        'personne' => array('personne_id' => 'id'),
        'commission_fonction' => array('commission_fonction_id' => 'id'),
        'activite' => array('activite_id' => 'id'),
        'departement' => array('departement_id' => 'id')
    );
}
