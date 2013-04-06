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
        'personne_denomination_id' => 'personne_denomination_id',
        'etatcivil_id' => 'etatcivil_id',
        'pays_id' => 'pays_id',
        'canton_id' => 'canton_id',
        'permis_id' => 'permis_id',
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
        'personne_denomination' => 'LEFT JOIN personnes_denominations ON (candidats.personne_denomination_id = personnes_denominations.id)',
        'etatcivil' => 'LEFT JOIN etatscivils ON (candidats.etatcivil_id = etatscivils.id)',
        //'pays' => 'LEFT JOIN pays ON (candidats.pays_pro_id = pays.id)',
        //'pays' => 'LEFT JOIN pays ON (candidats.pays_pri_id = pays.id)',
        'pays' => 'LEFT JOIN pays ON (candidats.pays_id = pays.id)',
        'canton' => 'LEFT JOIN cantons ON (candidats.canton_id = cantons.id)',
        'permis' => 'LEFT JOIN permis ON (candidats.permis_id = permis.id)',
        'commission' => 'LEFT JOIN commissions ON (candidats.commission_id = commissions.id)'
    );

    var $join = array('pays');

    var $wheres = array(
        'query' => "{{commission_id}} = {commission_id} AND {{actif}} = 1 AND (1=0 [OR {{*}} LIKE {*}])"
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

    // Self-documentation
    var $description = 'candidats aux commissions';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'commission_id' => 'identifiant de la commission liée',
        'nom' => 'nom du candidat',
        'prenom' => 'prénom du candidat',
        'genre_id' => 'identifiant du genre',
        'personne_denomination_id' => 'identifiant de la dénomination',
        'etatcivil_id' => 'identifiant de l\'état civil',
        'pays_id' => 'identifiant du pays',
        'canton_id' => 'identifiant du canton',
        'permis_id' => 'identifiant du permis de séjour',
        'date_naissance' => 'date de naissance',
        'nombre_enfants' => 'nombre d\'enfants',
        'no_avs' => 'numéro AVS',
        'adresse_pro' => 'adresse (professionnelle)',
        'npa_pro' => 'code postal (professionnel)',
        'lieu_pro' => 'lieu (professionnel)',
        'pays_pro_id' => 'identifiant du pays (professionnel)',
        'telephone_pro_countrycode' => 'code pays téléphonique (professionnel)',
        'telephone_pro' => 'numéro de téléphone (professionnel)',
        'email_pro' => 'email (professionnel)',
        'adresse_pri' => 'adresse (privé)',
        'npa_pri' => 'code postal (privé)',
        'lieu_pri' => 'lieu (privé)',
        'pays_pri_id' => 'identifiant du pays (privé)',
        'telephone_pri_countrycode' => 'code pays téléphonique (privé)',
        'telephone_pri' => 'numéro de téléphone (privé)',
        'email_pri' => 'email (privé)',
        'adresse_defaut' => 'adresse par défaut (privée/professionnelle)',
        'position_actuelle_fonction' => 'position actuelle',
        'position_actuelle_lieu' => 'lieu de la position actuelle'
    );
}
