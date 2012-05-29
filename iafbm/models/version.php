<?php
/**
 * This model stores tables write activity.
 */
class VersionModel extends iaJournalingModelMysql {

    var $versioning = false;

    var $table = 'versions';

    var $mapping = array(
        'id' => 'id',
        'created' => 'created',
        'creator' => 'creator',
        'table_name' => 'table_name',
        'id_field_name' => 'id_field_name',
        'id_field_value' => 'id_field_value',
        'model_name' => 'model_name',
        'operation' => 'operation',
        'commentaire' => 'commentaire'
    );

    var $primary = array('id');

    var $validation = array();

    function current() {
        $r = xModel::load('version', array(
            'xorder_by' => 'id',
            'xorder' => 'DESC',
            'xlimit' => 1
        ))->get(0);
        $v = $r['id'];
        if (!$v) throw new xException('Could not retrieve current version', 500);
        return $v;
    }
}