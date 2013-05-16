<?php

class CommissionCreationEtatModel extends iaModelMysql {

    var $table = 'commissions_creations_etats';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'nom' => 'nom'
    );

    var $primary = array('id');

    var $order_by = array('nom');

    var $validation = array(
        'nom' => array('mandatory'),
    );
}
