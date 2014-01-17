<?php

/**
 * @package iafbm
 * @subpackage model
 */
class ScriptChuvModel extends iaModelMysql {

    var $table = 'scripts_deltaChuv';

    var $mapping = array(
        'id' => 'id',
        'modif_id' => 'modif_id',
        'operation' => 'operation',
        'date' => 'date',
        'log' => 'log',        
    );

    var $order_by = array('date');

    var $primary = array('id');
}
