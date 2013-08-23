<?php

/**
 * @package iafbm
 * @subpackage model
 */
class ActiviteTypeModel extends iaModelMysql {

    var $table = 'activites_types';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'nom' => 'nom'
    );

    var $primary = array('id');

    var $validation = array(
        'nom' => 'mandatory'
    );

    // Self-documentation
    var $description = 'catalogue des type d\'activité';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'nom' => 'nom du type d\'activité'
    );
}
