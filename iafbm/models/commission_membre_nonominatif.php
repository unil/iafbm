<?php

class CommissionMembreNonominatifModel extends iaModelMysql {

    var $table = 'commissions_membres_nonominatifs';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'nom_prenom' => 'nom_prenom',
        'commission_id' => 'commission_id',
        'commission_fonction_id' => 'commission_fonction_id',
        'fonction_complement' => 'fonction_complement',
        'personne_denomination_id' => 'personne_denomination_id',
        'activite_id' => 'activite_id',
        'rattachement_id' => 'rattachement_id',
    );

    var $primary = array('id');

    var $joins = array(
        'commission_fonction' => 'LEFT JOIN commissions_fonctions ON (commissions_membres_nonominatifs.commission_fonction_id = commissions_fonctions.id)',
        'personne_denomination' => 'LEFT JOIN personnes_denominations ON (commissions_membres_nonominatifs.personne_denomination_id = personnes_denominations.id)',
        'activite' => 'LEFT JOIN activites ON (commissions_membres_nonominatifs.activite_id = activites.id)',
        'activite_nom' => 'LEFT JOIN activites_noms ON (activites.activite_nom_id = activites_noms.id)',
        'rattachement' => 'LEFT JOIN rattachements ON (commissions_membres_nonominatifs.rattachement_id = rattachements.id)',
        'commission' => 'LEFT JOIN commissions ON (commissions_membres_nonominatifs.commission_id = commissions.id)',
        'commission_type' => 'LEFT JOIN commissions_types ON (commissions.commission_type_id = commissions_types.id)',
        'commission_etat' => 'LEFT JOIN commissions_etats ON (commissions.commission_etat_id = commissions_etats.id)',
        'section' => 'LEFT JOIN sections ON (commissions.section_id = sections.id)'
    );

    var $join = array('commission', 'commission_fonction', 'activite', 'activite_nom', 'rattachement');

    var $wheres = array(
        'query' => "commissions_membres_nonominatifs.actif = 1 AND (1=0 [OR {{*}} LIKE {*}])"
    );

    var $validation = array(
        'commission_fonction_id' => 'mandatory'
    );

    var $archive_foreign_models = array(
        'commission_fonction' => array('commission_fonction_id' => 'id'),
        'activite' => array('activite_id' => 'id'),
        'rattachement' => array('rattachement_id' => 'id')
    );

    // Self-documentation
    var $description = 'membres non nominatifs des commissions';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'nom_prenom' => 'nom et prénom',
        'commission_id' => 'identifiant de la commission',
        'commission_fonction_id' => 'identifiant de la fonction au sein de la commission',
        'fonction_complement' => 'complément de fonction',
        'personne_denomination_id' => 'identifiant de la dénomination du membre',
        'activite_id' => 'identifiant de l\'activité du membre',
        'rattachement_id' => 'identifiant du rattachement organisationel'
    );
}