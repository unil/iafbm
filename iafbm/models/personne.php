<?php

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
        'etatcivil' => 'LEFT JOIN etatscivils ON (candidats.etatcivil_id = etatscivils.id)',
        'canton' => 'LEFT JOIN cantons ON (personnes.canton_id = cantons.id)',
        'pays' => 'LEFT JOIN pays ON (personnes.pays_id = pays.id)',
        'permis' => 'LEFT JOIN permis ON (personnes.permis_id = permis.id)'
    );

    var $join = 'pays';

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
}
