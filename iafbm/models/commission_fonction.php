<?php

class CommissionFonctionModel extends iaModelMysql {

    var $table = 'commissions_fonctions';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'nom' => 'nom',
        'description' => 'description',
        'position' => 'position'
    );

    var $primary = array('id');

    var $order_by = array('nom');

    var $validation = array(
        'nom' => array(
            'mandatory'
        )
    );

    // Self-documentation
    var $description = 'catalogue des fonctions des membres de commissions';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'nom' => 'nom de la fonction',
        'description' => 'description de la fonction',
        'position' => 'position relative'
    );
}
