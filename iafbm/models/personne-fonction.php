<?php

class PersonneFonctionModel extends xModelMysql {

    var $table = 'personnes_fonctions';

    var $mapping = array(
        'id' => 'id',
        'personne_id' => 'personne_id',
        'section_id' => 'section_id',
        'titre-academique_id' => 'titre_academique_id',
        'taux_activite' => 'taux_activite',
        'date_contrat' => 'date_contrat',
        'debut_mandat' => 'debut_mandat',
        'fonction-hospitaliere_id' => 'fonction_hospitaliere_id',
        'departement_id' => 'departement_id'
    );

    var $primary = array('id');
}
