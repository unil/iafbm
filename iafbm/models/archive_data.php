<?php
/**
 * This model stores tables row archives data.
 * @note No need to extend iaJournalingModelMysql for security reasons
 *       because 'archive' tables are not exposed through a model.
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

    // Self-documentation
    var $description = 'données des enregistrements archivés';
    var $labels = array(
        'id' => 'identifiant interne',
        'archive_id' => 'identifiant interne d\'archive',
        'table_name' => 'nom de la table',
        'model_name' => 'nom du modèle',
        'table_field_name' => 'nom du champs (au niveau base de données)',
        'model_field_name' => 'nom du champs (au niveau du modèle)',
        'id_field_name' => 'nom du champs identifiant',
        'id_field_value' => 'valeur du champs identifiant',
        'value' => 'valeur du champs archivé'
    );
}
