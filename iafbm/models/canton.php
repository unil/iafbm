<?php

/**
 * @package iafbm
 * @subpackage model
 */
class CantonModel extends iaModelMysql {

    var $table = 'cantons';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'code' => 'code',
        'nom' => 'nom'
    );

    var $primary = array('id');

    var $order_by = array('nom');

    // Self-documentation
    var $description = 'catalogue des cantons';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'code' => 'code canton (sur 2 lettres)',
        'nom' => 'nom du canton'
    );
}
