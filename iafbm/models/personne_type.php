<?php

/**
 * @package iafbm
 * @subpackage model
 */
class PersonneTypeModel extends iaModelMysql {

    var $table = 'personnes_types';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'nom' => 'nom'
    );

    var $order_by = array('nom');

    var $primary = array('id');

    // Self-documentation
    var $description = 'Catalogue des type de personnes';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'nom' => 'nom du type de personne'
    );
}
