<?php

class RattachementModel extends iaModelMysql {

    var $table = 'rattachements';

    var $mapping = array(
        'id' => 'id',
        'id_unil' => 'id_unil',
        'id_chuv' => 'id_chuv',
        'actif' => 'actif',
        'section_id' => 'section_id',
        'nom' => 'nom'
    );

    var $primary = array('id');

    var $joins = array(
        'section' => 'LEFT JOIN sections ON (rattachements.section_id = sections.id)',
    );

    var $join = array('section');

    var $validation = array(
        'nom' => 'mandatory'
    );

    var $archive_foreign_models = array(
        'section' => array('section_id' => 'id')
    );
}
