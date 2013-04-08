<?php

/**
 * @package iafbm
 * @subpackage model
 */
class CommissionTravailModel extends iaModelMysql {

    var $table = 'commissions_travails';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'commission_id' => 'commission_id',
        'termine' => 'termine',
        'primo_loco' => 'loco_primo',
        'secondo_loco' => 'loco_secondo',
        'tertio_loco' => 'loco_tertio',
        'commentaire' => 'commentaire'
    );

    var $primary = array('id');

    var $validation = array(
        'commission_id' => 'mandatory'
    );

    // Self-documentation
    var $description = 'phase de travail des commissions';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'commission_id' => 'identifiant de la commission',
        'termine' => 'phase terminée',
        'primo_loco' => 'identifiant du candidat primo loco',
        'secondo_loco' => 'identifiant du candidat secundo loco',
        'tertio_loco' => 'identifiant du candidat tertio loco',
        'commentaire' => 'commentaire'
    );
}
