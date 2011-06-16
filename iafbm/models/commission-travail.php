<?php

class CommissionTravailModel extends xModelMysql {

    var $table = 'commissions_travails';

    var $mapping = array(
        'id' => 'id',
        'created' => 'created',
        'modified' => 'modified',
        'actif' => 'actif',
        'commission_id' => 'commission_id',
        'loco_primo' => 'loco_primo',
        'loco_secondo' => 'loco_secondo',
        'loco_tertio' => 'loco_tertio'
    );

    var $primary = array('id');

    var $validation = array(
        'commission_id' => 'mandatory'
    );
}
