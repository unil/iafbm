<?php
/**
 * This model stores tables write activity
 * @note This Model does not extends iaModelMysql because wo do not want to version history
 */
class ArchiveModel extends iaModelMysql {

    var $table = 'archives';

    var $versioning = false;

    var $mapping = array(
        'id' => 'id',
        'created' => 'created',
        'creator' => 'creator',
        'table_name' => 'table_name',
        'id_field_name' => 'id_field_name',
        'id_field_value' => 'id_field_value',
        'model_name' => 'model_name'
    );

    var $primary = array('id');

    var $validation = array();

    function post() {
        throw new xException("{{$this->name} model cannot be modified", 403);
    }
}