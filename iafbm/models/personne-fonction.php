<?php

class PersonneFonctionModel extends xModelMysql {

    var $table = 'personnes_fonctions';

    var $mapping = array(
        'id' => 'id',
        'nom' => 'nom'
    );

    var $order_by = array('nom');

    var $primary = array('id');
}
