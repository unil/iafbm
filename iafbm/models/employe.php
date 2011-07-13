<?php

class EmployeModel extends iaModelMysql {

    var $table = 'employes';

    var $mapping = array(
        'id' => 'id',
        'personne_id' => 'personne_id',
        'section' => 'section',
        'date_retraite' => 'date_retraite',
        'created' => 'created',
        'modified' => 'modified',
        'util_creat' => 'util_creat',
        'util_modif' => 'util_modif'
    );

    var $primary = array('id');

    var $joins = array(
        'personne' => 'LEFT JOIN personnes ON (employes.personne_id = personnes.id)'
    );

    var $join = 'personne';

    var $validation = array(
        'section' => array(
            'mandatory'
        ),
    );
}
