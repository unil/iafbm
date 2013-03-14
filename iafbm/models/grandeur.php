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
}
