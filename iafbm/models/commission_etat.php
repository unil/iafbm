<?php

/**
 * @package iafbm
 * @subpackage model
 */
class CommissionEtatModel extends iaModelMysql {

    var $table = 'commissions_etats';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'nom' => 'nom',
        'description' => 'description'
    );

    var $primary = array('id');

    var $order_by = array('id');
    var $order = 'ASC';

    var $validation = array(
        'nom' => array(
            'mandatory'
        )
    );

    // Self-documentation
    var $description = 'catalogue des états de commissions';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'nom' => 'nom de l\'état',
        'description' => 'description de l\'état'
    );
}
