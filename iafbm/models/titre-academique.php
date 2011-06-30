<?php

class TitreAcademiqueModel extends xModelMysql {

    var $table = 'titres_academiques';

    var $mapping = array(
        'id' => 'id',
        'abreviation' => 'abreviation',
        'nom' => 'nom'
    );

    var $order_by = array('id');

    var $primary = array('id');
}
