<?php
/**
 * This model stores tables write activity
 * @note This Model does not extends iaModelMysql because wo do not want to version history
 */
class VersionDataModel extends iaModelMysql {

    var $versioning = false;

    var $table = 'versions_data';

    var $mapping = array(
        'id' => 'id',
        'version_id' => 'version_id',
        'field_name' => 'field_name',
        'old_value' => 'old_value',
        'new_value' => 'new_value'
    );

    var $primary = array('id');

    var $joins = array(
        'version' => 'LEFT JOIN versions ON (versions_data.version_id = versions.id)'
    );

    var $join = array('version');

    var $validation = array();
}
