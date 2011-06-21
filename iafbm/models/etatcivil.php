<?php

class EtatCivilModel extends xModelMysql {

    var $table = 'etatscivils';

    var $mapping = array(
        'id' => 'id',
        'nom' => 'nom'
    );

    var $order_by = array('id');

    var $primary = array('id');
}
