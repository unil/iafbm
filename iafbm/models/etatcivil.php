<?php

class EtatCivilModel extends iaModelMysql {

    var $table = 'etatscivils';

    var $versioning = false;

    var $mapping = array(
        'id' => 'id',
        'nom' => 'nom'
    );

    var $order_by = array('id');

    var $primary = array('id');
}
