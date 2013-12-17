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
        'log' => 'log',
        'rattachement_id' => 'rattachement_id',
        
    );

    var $order_by = array('date');

    var $primary = array('id');
}
