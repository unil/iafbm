<?php

class PersonneDenominationModel extends iaModelMysql {

    var $table = 'personnes_denominations';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'nom' => 'nom',
        'nom_masculin' => 'nom_masculin',
        'nom_feminin' => 'nom_feminin',
        'abreviation' => 'abreviation',
        'abreviation_masculin' => 'abreviation_masculin',
        'abreviation_feminin' => 'abreviation_feminin',
        'poids' => 'poids'
    );

    var $order_by = array('poids');

    var $primary = array('id');

    var $validations = array(
        'nom' => 'mandatory',
        'nom_masculin' => 'mandatory',
        'nom_feminin' => 'mandatory',
        'abreviation' => 'mandatory',
        'abreviation_masculin' => 'mandatory',
        'abreviation_feminin' => 'mandatory',
        'poids' => 'mandatory'
    );
}
