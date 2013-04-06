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

    // Self-documentation
    var $description = 'références aux enregistrement archivés';
    var $labels = array(
        'id' => 'identifiant interne',
        'created' => 'date de création',
        'creator' => 'identifiant Switch-AAI l\'utilisateur créateur',
        'table_name' => 'nom de la table',
        'id_field_name' => 'nom du champs identifiant',
        'id_field_value' => 'valeur du champs identifiant',
        'model_name' => 'nom du modèle'
    );
}