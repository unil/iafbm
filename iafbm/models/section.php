<?php

/**
 * @package iafbm
 * @subpackage model
 */
class SectionModel extends iaModelMysql {

    var $table = 'sections';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'code' => 'code',
        'nom' => 'nom'
    );

    var $order_by = array('nom');

    var $primary = array('id');

    // Self-documentation
    var $description = 'catalogue des sections organisationnelles';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'code' => 'code de la section',
        'nom' => 'nom de la section'
    );
}
