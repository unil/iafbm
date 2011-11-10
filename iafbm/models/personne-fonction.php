<?php

class PersonneFonctionModel extends iaModelMysql {

    var $table = 'personnes_fonctions';

    var $mapping = array(
        'id' => 'id',
        'personne_id' => 'personne_id',
        'section_id' => 'section_id',
        'titre-academique_id' => 'titre_academique_id',
        'taux_activite' => 'taux_activite',
        'date_contrat' => 'date_contrat',
        'debut_mandat' => 'debut_mandat',
        'fin_mandat' => 'fin_mandat',
        'fonction-hospitaliere_id' => 'fonction_hospitaliere_id',
        'departement_id' => 'departement_id'
    );

    var $primary = array('id');

    var $order_by = 'fin_mandat';
    var $order = 'ASC';

    var $joins = array(
        'personne' => 'LEFT JOIN personnes ON (personnes_fonctions.personne_id = personnes.id)',
        'titre-academique' => 'LEFT JOIN titres_academiques ON (personnes_fonctions.titre_academique_id = titres_academiques.id)',
        'fonction-hospitaliere' => 'LEFT JOIN fonctions_hospitalieres ON (personnes_fonctions.fonction_hospitaliere_id = fonctions_hospitalieres.id)',
        'departement' => 'LEFT JOIN departements ON (personnes_fonctions.departement_id = departements.id)'
    );

    var $join = array('personne', 'titre-academique', 'fonction-hospitaliere', 'departement');

    var $wheres = array(
        'query' => "{{personne_id}} = {personne_id} AND (1=0 [OR {{*}} LIKE {*}])"
    );
}
