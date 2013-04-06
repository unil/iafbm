<?php

class ActiviteNomModel extends iaModelMysql {

    var $table = 'activites_noms';

    var $mapping = array(
        'id' => 'id',
        'id_unil' => 'id_unil',
        'id_chuv' => 'id_chuv',
        'actif' => 'actif',
        'nom' => 'nom',
        'abreviation' => 'abreviation'
    );

    var $primary = array('id');

    var $validation = array(
        'nom' => 'mandatory',
        'abreviation' => 'mandatory'
    );

    // Self-documentation
    var $description = 'catalogue des noms d\'activité';
    var $labels = array(
        'id' => 'identifiant interne',
        'id_unil' => 'identifiant UNIL',
        'id_chuv' => 'identifiant CHUV',
        'actif' => 'enregistrement actif',
        'nom' => 'nom d\'activité',
        'abreviation' => 'abreviation de l\'activité'
    );
}
