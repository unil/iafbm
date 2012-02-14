<?php
/**
 * This model stores tables write activity
 * @note This Model does not extends iaModelMysql because wo do not want to version history
 */
class ArchiveDataModel extends iaModelMysql {

    var $table = 'archives_data';

    var $versioning = false;

    var $mapping = array(
        'id' => 'id',
        'archive_id' => 'archive_id',
        'table_name' => 'table_name',
        'model_name' => 'model_name',
        'table_field_name' => 'table_field_name',
        'model_field_name' => 'model_field_name',
        'id_field_name' => 'id_field_name',
        'id_field_value' => 'id_field_value',
        'value' => 'value'
    );

    var $primary = array('id');

    var $joins = array(
        'archive' => 'LEFT JOIN archives ON (archives_data.archive_id = archives.id)'
    );

    var $join = array('archive');

    var $validation = array(
        'archive_id' => 'mandatory',
        'table_field_name' => 'mandatory',
        'model_field_name' => 'mandatory'
    );

    function post() {
        throw new xException("{{$this->name} model cannot be modified", 403);
    }
}
