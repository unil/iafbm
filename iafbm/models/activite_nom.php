<?php

class ActiviteNomModel extends iaModelMysql {

    var $table = 'activites_noms';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'nom' => 'nom',
        'abreviation' => 'abreviation'
    );

    var $primary = array('id');

    var $validation = array(
        'nom' => 'mandatory',
        'abreviation' => 'mandatory'
    );
}
