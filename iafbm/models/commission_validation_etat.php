<?php

class CommissionValidationEtatModel extends iaModelMysql {

    var $table = 'commissions_validations_etats';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'created' => 'created',
        'modified' => 'modified',
        'nom' => 'nom'
    );

    var $primary = array('id');

    var $order_by = array('nom');

    var $validation = array(
        'nom' => array('mandatory'),
    );
}
