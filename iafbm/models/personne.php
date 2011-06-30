<?php

class PersonneModel extends xModelMysql {

    var $table = 'personnes';

    var $mapping = array(
        'id' => 'id',
        'id_unil' => 'id_unil',
        'id_chuv' => 'id_chuv',
        'id_adifac' => 'id_adifac',

        'actif' => 'actif',
        'created' => 'created',
        'modified' => 'modified',

        'personne-type_id' => 'personne_type_id',

        'nom' => 'nom',
        'prenom' => 'prenom',
        'genre_id' => 'genre_id',
        'date_naissance' => 'date_naissance',
        'no_avs' => 'no_avs',
        'canton_id' => 'canton_id',
        'pays_id' => 'pays_id',
        'permis_id' => 'permis_id',

        //'adresse' => 'adresse',
        'tel' => 'tel',
        'email' => 'email',
        'titre_lecon_inaug' => 'titre_lecon_inaug',
        'date_lecon_inaug' => 'date_lecon_inaug',
    );

    var $primary = array('id');

    var $joins = array(
        'pays' => 'LEFT JOIN pays ON (personnes.pays_id = pays.id)'
    );

    var $join = 'pays';

    var $validation = array(
        'nom' => array(
            'mandatory'
        ),
/*
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
}
