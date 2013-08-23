<?php

/**
 * @package iafbm
 * @subpackage model
 */
class AdresseTypeModel extends iaModelMysql {

    var $table = 'adresses_types';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'nom' => 'nom'
    );

    var $primary = array('id');

    var $order_by = array('id');

    // Self-documentation
    var $description = 'catalogue de types d\'adresses';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'nom' => 'nom du type d\'adresse'
    );
}
