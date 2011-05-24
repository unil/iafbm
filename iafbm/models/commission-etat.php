<?php

class CommissionEtatModel extends xModelMysql {

    var $table = 'commissions_etats';

    var $mapping = array(
        'id' => 'id',
        'nom' => 'nom',
        'description' => 'description'
    );

    var $primary = array('id');

    var $validation = array(
        'nom' => array(
            'mandatory'
        )
    );
}
