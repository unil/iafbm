<?php

/**
 * @package iafbm
 * @subpackage model
 */
class PersonneModel extends iaModelMysql {

    var $table = 'personnes';

    var $mapping = array(
        'id' => 'id',
        'id_unil' => 'id_unil',
        'id_chuv' => 'id_chuv',
        'id_adifac' => 'id_adifac',

        'actif' => 'actif',

        'personne_type_id' => 'personne_type_id',

        'nom' => 'nom',
        'prenom' => 'prenom',
        'genre_id' => 'genre_id',
        'personne_denomination_id' => 'personne_denomination_id',
        'etatcivil_id' => 'etatcivil_id',
        'date_naissance' => 'date_naissance',
        'no_avs' => 'no_avs',
        'canton_id' => 'canton_id',
        'pays_id' => 'pays_id',
        'permis_id' => 'permis_id',
    );

    var $primary = array('id');

    var $joins = array(
        'personne_type' => 'LEFT JOIN personnes_types ON (personnes.personne_type_id = personnes_types.id)',
        'genre' => 'LEFT JOIN genres ON (personnes.genre_id = genres.id)',
        'personne_denomination' => 'LEFT JOIN personnes_denominations ON (personnes.personne_denomination_id = personnes_denominations.id)',
        'etatcivil' => 'LEFT JOIN etatscivils ON (personnes.etatcivil_id = etatscivils.id)',
        'canton' => 'LEFT JOIN cantons ON (personnes.canton_id = cantons.id)',
        'pays' => 'LEFT JOIN pays ON (personnes.pays_id = pays.id)',
        'permis' => 'LEFT JOIN permis ON (personnes.permis_id = permis.id)'
    );

    var $join = 'pays';

    var $wheres = array(
        'query' => 'common/model/query'
    );

    var $validation = array(
        //'personne_type_id' => 'mandatory',
        'nom' => 'mandatory',
        'prenom' => 'mandatory'
    );

    var $archive_foreign_models = array(
        'personne_type' => array('personne_type_id' => 'id'),
        'genre' => array('genre_id' => 'id'),
        'canton' => array('canton_id' => 'id'),
        'pays' => array('pays_id' => 'id'),
        'permis' => array('permis_id' => 'id'),
        'personne_adresse' => 'personne_id',
        'personne_email' => 'personne_id',
        'personne_telephone' => 'personne_id',
        'personne_formation' => 'personne_id',
        'personne_activite' => 'personne_id'
    );

    // Self-documentation
    var $description = 'personnes';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'id_unil' => 'identifiant UNIL',
        'id_chuv' => 'identifiant CHUV',
        'id_adifac' => 'identifiant ADIFAC',
        'actif' => 'actif',
        'personne_type_id' => 'identifiant du type de personne',
        'nom' => 'nom de la personne',
        'prenom' => 'prénom de la personne',
        'genre_id' => 'identifiant du genre de la personne',
        'personne_denomination_id' => 'identifiant de la denomination de la personne',
        'etatcivil_id' => 'identifiant de l\'état civil de la personne',
        'date_naissance' => 'date de naissance de la personne',
        'no_avs' => 'no AVS',
        'canton_id' => 'identifiant du canton',
        'pays_id' => 'identifiant du pays',
        'permis_id' => 'identifiant du permis de séjour'
    );
}
