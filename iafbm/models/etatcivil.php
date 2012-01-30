<?php

class EtatCivilModel extends iaModelMysql {

    var $table = 'etatscivils';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'created' => 'created',
        'modified' => 'modified',
        'nom' => 'nom'
    );

    var $order_by = array('id');

    var $primary = array('id');
}
