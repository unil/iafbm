<?php

class ActiviteTypeModel extends iaModelMysql {

    var $table = 'activites_types';

    var $mapping = array(
        'id' => 'id',
        'nom' => 'nom'
    );

    var $primary = array('id');

    var $validation = array(
        'nom' => 'mandatory'
    );
}
