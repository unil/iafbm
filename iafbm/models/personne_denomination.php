<?php

class PersonneDenominationModel extends iaModelMysql {

    var $table = 'personnes_denominations';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'nom' => 'nom'
    );

    var $order_by = array('nom');

    var $primary = array('id');

    var $validations = array(
        'nom' => 'mandatory'
    );
}
