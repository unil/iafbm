<?php

class CommissionValidationEtatModel extends xModelMysql {

    var $table = 'commissions_validations_etats';

    var $mapping = array(
        'id' => 'id',
        'nom' => 'nom',
    );

    var $primary = array('id');

    var $order_by = array('nom');

    var $validation = array(
        'nom' => array('mandatory'),
    );
}
