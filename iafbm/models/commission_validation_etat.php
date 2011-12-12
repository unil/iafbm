<?php

class CommissionValidationEtatModel extends iaModelMysql {

    var $table = 'commissions_validations_etats';

    var $versioning = false;

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
