<?php

/**
 * @package iafbm
 * @subpackage model
 */
class EtatCivilModel extends iaModelMysql {

    var $table = 'etatscivils';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'nom' => 'nom'
    );

    var $order_by = array('id');

    var $primary = array('id');

    // Self-documentation
    var $description = 'catalogue des états civils';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'nom' => 'nom'
    );
}
