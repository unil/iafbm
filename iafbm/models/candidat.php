<?php

class CandidatModel extends iaModelMysql {

    var $table = 'candidats';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'commission_id' => 'commission_id',
        'nom' => 'nom',
        'prenom' => 'prenom',
        'genre_id' => 'genre_id',
        'etatcivil_id' => 'etatcivil_id',
        'pays_id' => 'pays_id',
        'date_naissance' => 'date_naissance',
        'nombre_enfants' => 'nombre_enfants',
        'no_avs' => 'no_avs',
        'adresse_pro' => 'adresse_pro',
        'npa_pro' => 'npa_pro',
        'lieu_pro' => 'lieu_pro',
        'pays_pro_id' => 'pays_pro_id',
        'telephone_pro_countrycode' => 'telephone_pro_countrycode',
        'telephone_pro' => 'telephone_pro',
        'email_pro' => 'email_pro',
        'adresse_pri' => 'adresse_pri',
        'npa_pri' => 'npa_pri',
        'lieu_pri' => 'lieu_pri',
        'pays_pri_id' => 'pays_pri_id',
        'telephone_pri_countrycode' => 'telephone_pri_countrycode',
        'telephone_pri' => 'telephone_pri',
        'email_pri' => 'email_pri',
        'adresse_defaut' => 'adresse_defaut',
        'position_actuelle_fonction' => 'position_actuelle_fonction',
        'position_actuelle_lieu' => 'position_actuelle_lieu'
    );

    var $primary = array('id');

    //FIXME: pays model is referenced by two different columns, but can only be specified once as a key indice in $joins array :(
    //       Solution: in this file (that is, locally), add:
    //                 create a PaysProModel + PaysPriModel extends PaysModel {}
    var $joins = array(
        'genre' => 'LEFT JOIN genres ON (candidats.genre_id = genres.id)',
        'etatcivil' => 'LEFT JOIN etatscivils ON (candidats.etatcivil_id = etatscivils.id)',
        //'pays' => 'LEFT JOIN pays ON (candidats.pays_pro_id = pays.id)',
        //'pays' => 'LEFT JOIN pays ON (candidats.pays_pri_id = pays.id)',
        'pays' => 'LEFT JOIN pays ON (candidats.pays_id = pays.id)',
        'commission' => 'LEFT JOIN commissions ON (candidats.commission_id = commissions.id)'
    );

    var $join = array('pays');

    var $wheres = array(
        'query-fulltext' => "models/common/query-fulltext"
    );

    var $validation = array(
        'nom' => 'mandatory',
        'prenom' => 'mandatory',
        'email_pro' => 'email',
        'email_pri' => 'email'
    );

    var $archive_foreign_models = array(
        'genre' => array('genre_id' => 'id'),
        'etatcivil' => array('etatcivil_id' => 'id'),
        'pays' => array('pays_id' => 'id'),
        // FIXME: 'pays' => array('pays_pro_id' => 'id'),
        // FIXME: 'pays' => array('pays_pri_id' => 'id'),
        'candidat_formation' => 'candidat_id'
    );
}
