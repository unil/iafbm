<?php

class FonctionHospitaliereModel extends iaModelMysql {

    var $table = 'fonctions_hospitalieres';

    var $mapping = array(
        'id' => 'id',
        'nom' => 'nom'
    );

    var $primary = array('id');

    var $validation = array(
        'nom' => 'mandatory'
    );
}
