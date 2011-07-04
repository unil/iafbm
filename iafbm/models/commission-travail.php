<?php

class CommissionTravailModel extends xModelMysql {

    var $table = 'commissions_travails';

    var $mapping = array(
        'id' => 'id',
        'created' => 'created',
        'modified' => 'modified',
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
}
