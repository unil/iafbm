<?php

class FormationModel extends xModelMysql {

    var $table = 'formations';

    var $mapping = array(
        'id' => 'id',
        'abreviation' => 'abreviation'
    );

    var $primary = array('id');

    var $validation = array(
        'abreviation' => 'mandatory'
    );
}
