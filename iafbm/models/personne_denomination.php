<?php

/**
 * @package iafbm
 * @subpackage model
 */
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

    // Self-documentation
    var $description = 'catalogue des dénominations';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'nom' => 'nom de la dénomination',
        'nom_masculin' => 'nom au masculin',
        'nom_feminin' => 'nom au feminin',
        'abreviation' => 'abréviation',
        'abreviation_masculin' => 'abréviation au masculin',
        'abreviation_feminin' => 'abréviation au feminin',
        'poids' => 'poids'
    );
}