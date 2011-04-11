<?php

class PersonneModel extends xModelMysql {

    var $table = 'personnes';

    var $mapping = array(
        'id' => 'id',
        'id_unil' => 'id_unil',
        'id_chuv' => 'id_chuv',
        'id_adifac' => 'id_adifac',
        'nom' => 'nom',
        'prenom' => 'prenom',
        'adresse' => 'adresse',
        'tel' => 'tel',
        'email' => 'email',
        'date_naissance' => 'date_naissance',
        'etat_civil' => 'etat_civil',
        'sexe' => 'sexe',
        'pays_id' => 'pays_id',
        'cantons_id' => 'cantons_id',
        'permis_id' => 'permis_id',
        'titre_lecon_inaug' => 'titre_lecon_inaug',
        'date_lecon_inaug' => 'date_lecon_inaug',
        'etat' => 'etat',
        'created' => 'created',
        'modified' => 'modified'
    );

    var $validation = array(
/*
        'begin' => array(
            'mandatory',
            'datetime'
        ),
        'end' => array(
            'mandatory',
            'datetime'
        ),
        'zip' => array(
            'mandatory',
            'integer',
            'minlength' => array('length'=>4),
            'maxlength' => array('length'=>4)
        ),
        'location' => array(
            'mandatory'
        ),
        'distance' => array(
            'mandatory',
            'integer',
            'minvalue' => array('length'=>0)
        ),
        'profile' => array(
            'mandatory',
            'integer'
        ),
*/
    );

    var $primary = array('id');

    var $joins = array(
        //'supplier' => 'LEFT JOIN profile_supplier ON (availability.fk_profile = profile_supplier.id)'
    );
}
