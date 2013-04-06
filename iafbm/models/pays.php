<?php

class PaysModel extends iaModelMysql {

    var $table = 'pays';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'code' => 'code',
        'nom' => 'nom',
        'nom_en' => 'nom_en'
    );

    var $order_by = array('nom');

    var $primary = array('id');

    // Self-documentation
    var $description = 'catalogue des pays';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'code' => 'code pays',
        'nom' => 'nom du pays',
        'nom_en' => 'nom du pays en anglais'
    );
}
