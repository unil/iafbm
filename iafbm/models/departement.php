<?php

class DepartementModel extends iaModelMysql {

    var $table = 'departements';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'created' => 'created',
        'modified' => 'modified',
        'section_id' => 'section_id',
        'nom' => 'nom'
    );

    var $primary = array('id');

    var $joins = array(
        'section' => 'LEFT JOIN sections ON (departements.section_id = sections.id)',
    );

    var $join = array('section');

    var $validation = array(
        'nom' => 'mandatory'
    );
}
