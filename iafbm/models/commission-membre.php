<?php

class CommissionMembreModel extends xModelMysql {

    var $table = 'commissions_membres';

    var $mapping = array(
        'id' => 'id',
        'personne_id' => 'personne_id',
        'commission_id' => 'commission_id',
        'fonction_id' => 'commission_fonction_id',
        'titre-academique_id' => 'titre_academique_id',
        'departement_id' => 'departement_id',
        'actif' => 'actif',
        'created' => 'created',
        'modified' => 'modified'
    );

    var $primary = array('id');

    var $joins = array(
        'personne' => 'LEFT JOIN personnes ON (commissions_membres.personne_id = personnes.id)',
        'commission' => 'LEFT JOIN commissions ON (commissions_membres.commission_id = commissions.id)',
        'commission-fonction' => 'LEFT JOIN commissions_fonctions ON (commissions_membres.commission_fonction_id = commissions_fonctions.id)',
        'titre-academique' => 'LEFT JOIN titres_academiques ON (commissions_membres.commission_fonction_id = titres_academiques.id)',
        'departement' => 'LEFT JOIN departements ON (commissions_membres.departement_id = departements.id)'
    );

    var $join = array('personne', 'commission', 'commission-fonction', 'titre-academique', 'departement');

    var $validation = array();
}
