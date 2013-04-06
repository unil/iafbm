<?php

class ActiviteModel extends iaModelMysql {

    var $table = 'activites';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'section_id' => 'section_id',
        'activite_type_id' => 'activite_type_id',
        'activite_nom_id' => 'activite_nom_id'
    );

    var $primary = array('id');

    var $joins = array(
        'section' => 'LEFT JOIN sections ON (activites.section_id = sections.id)',
        'activite_type' => 'LEFT JOIN activites_types ON (activites.activite_type_id = activites_types.id)',
        'activite_nom' => 'LEFT JOIN activites_noms ON (activites.activite_nom_id = activites_noms.id)'
    );

    var $join = array('section', 'activite_type', 'activite_nom');

    var $validation = array(
        'section_id' => 'mandatory',
        'activite_type_id' => 'mandatory',
        'activite_nom_id' => 'mandatory'
    );

    var $archive_foreign_models = array(
        'section' => array('section_id' => 'id'),
        'activite_type' => array('activite_type_id' => 'id'),
        'activite_nom' => array('activite_nom_id' => 'id')
    );

    // Self-documentation
    var $description = 'catalogue d\'activités académiques et cliniques';
    var $labels = array(
        'id' => 'identifian interne',
        'actif' => 'enregistrement actif',
        'section_id' => 'identifiant interne de section',
        'activite_type_id' => 'identifiant interne du type d\'activité',
        'activite_nom_id' => 'identifiant interne du nom d\'activité'
    );
}
