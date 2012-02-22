<?php

class PersonneActiviteModel extends iaModelMysql {

    var $table = 'personnes_activites';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'personne_id' => 'personne_id',
        'departement_id' => 'departement_id',
        'activite_id' => 'activite_id',
        'taux_activite' => 'taux_activite',
        'debut' => 'debut',
        'fin' => 'fin'
    );

    var $primary = array('id');

    var $order_by = 'fin';
    var $order = 'ASC';

    var $joins = array(
        'personne' => 'LEFT JOIN personnes ON (personnes_activites.personne_id = personnes.id)',
        'departement' => 'LEFT JOIN departements ON (personnes_activites.departement_id = departements.id)',
        'activite' => 'LEFT JOIN activites ON (personnes_activites.activite_id = activites.id)',
        'activite_nom' => 'LEFT JOIN activites_noms ON (activites.activite_nom_id = activites_noms.id)',
        'activite_type' => 'LEFT JOIN activites_types ON (activites.activite_type_id = activites_types.id)',
        'section' => 'LEFT JOIN sections ON (activites.section_id = sections.id)'
    );

    var $join = array('personne', 'departement', 'activite', 'activite_type', 'activite_nom', 'section');

    var $archive_foreign_models = array(
        'departement' => array('departement_id' => 'id'),
        'activite' => array('activite_id' => 'id'),
    );
}
