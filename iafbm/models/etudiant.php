<?php

class EtudiantModel extends xModelMysql {

    var $table = 'etudiants';

    var $mapping = array(
        'id' => 'id',
        'personne_id' => 'personne_id',
        'created' => 'created',
        'modified' => 'modified',
        'util_creat' => 'util_creat',
        'util_modif' => 'util_modif'
    );

    var $validation = array(
    );

    var $primary = array('id');

    var $joins = array(
        'personne' => 'LEFT JOIN personnes ON (etudiants.personne_id = personnes.id)'
    );

    var $join = 'personne';
}
