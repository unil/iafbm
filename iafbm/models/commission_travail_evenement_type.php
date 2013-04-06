<?php

class CommissionTravailEvenementTypeModel extends iaModelMysql {

    var $table = 'commissions_travails_evenements_types';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'nom' => 'nom'
    );

    var $primary = array('id');

    var $validation = array(
    );

    // Self-documentation
    var $description = 'catalogue des types d\'événements de la phase de travail des commissions';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'nom' => 'nom du type d\'événement'
    );
}
