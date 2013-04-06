<?php
/**
 * This model stores version relations between models.
 */
class VersionRelationModel extends iaJournalingModelMysql {

    var $versioning = false;

    var $table = 'versions_relations';

    var $mapping = array(
        'id' => 'id',
        'version_id' => 'version_id',
        'table_name' => 'table_name',
        'model_name' => 'model_name',
        'id_field_name' => 'id_field_name',
        'id_field_value' => 'id_field_value'
    );

    var $primary = array('id');

    var $joins = array(
        'version' => 'LEFT JOIN versions ON (versions_relations.version_id = versions.id)'
    );

    var $join = array('version');

    var $validation = array(
        'version_id' => 'mandatory',
        'table_name' => 'mandatory',
        'model_name' => 'mandatory',
        'id_field_name' => 'mandatory',
        'id_field_value' => 'mandatory'
    );

    // Self-documentation
    var $description = 'versions des enregistrement (relations)';
    var $labels = array(
        'id' => 'identifiant interne',
        'version_id' => 'identifiant de la version',
        'table_name' => 'nom de la table',
        'model_name' => 'nom du modèle',
        'id_field_name' => 'nom du champs identifiant',
        'id_field_value' => 'valeur du champs identifiant'
    );
}