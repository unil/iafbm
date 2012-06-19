<?php
/**
 * This model stores tables rows archives.
 * @note No need to extend iaJournalingModelMysql for security reasons
 *       because 'archive' tables are not exposed through a model.
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
}