<?php

/**
 * @package iafbm
 * @subpackage model
 */
class CommissionValidationEtatModel extends iaModelMysql {

    var $table = 'commissions_validations_etats';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'nom' => 'nom'
    );

    var $primary = array('id');

    var $order_by = array('nom');

    var $validation = array(
        'nom' => array('mandatory'),
    );

    // Self-documentation
    var $description = 'catalogue des états de la phase de validation des commissions';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'nom' => 'nom de l\'état'
    );
}
