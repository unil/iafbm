<?php

class GrandeurModel extends iaModelMysql {

    var $table = 'grandeurs';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'nom' => 'nom',
        'dimsension_symbole' => 'dimsension_symbole',
        'unite' => 'unite',
        'unite_singulier' => 'unite_singulier',
        'unite_pluriel' => 'unite_pluriel',
        'unite_symbole' => 'unite_symbole'
    );

    var $order_by = array('nom');

    var $primary = array('id');

    // Self-documentation
    var $description = 'catalogue des grandeurs';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'nom' => 'nom de la grandeur',
        'dimsension_symbole' => 'symbole de la dimension de la grandeur',
        'unite' => 'unite de la grandeur',
        'unite_singulier' => 'unité au singulier',
        'unite_pluriel' => 'unité au pluriel',
        'unite_symbole' => 'unité du symbole'
    );
}
