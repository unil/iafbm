<?php

class ActiviteModel extends iaModelMysql {

    var $table = 'activites';

    var $mapping = array(
        'id' => 'id',
        'activite_type_id' => 'activite_type_id',
        'section_id' => 'section_id',
        'abreviation' => 'abreviation',
        'nom' => 'nom'
    );

    var $primary = array('id');

    var $joins = array(
        'activite_type' => 'LEFT JOIN activites_types ON (activites.activite_type_id = activites_types.id)',
        'section' => 'LEFT JOIN sections ON (activites.section_id = sections.id)'
    );

    var $join = array('activite_type', 'section');

    var $validation = array(
        'abreviation' => 'mandatory',
        'nom' => 'mandatory'
    );

    var $archive_foreign_models = array(
        'activite_type' => array('activite_type_id' => 'id'),
        'section' => array('section_id' => 'id')
    );
}
