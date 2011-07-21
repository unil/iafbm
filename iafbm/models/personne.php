<?php

class PersonneModel extends iaModelMysql {

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
    );

    var $primary = array('id');

    var $joins = array(
        'pays' => 'LEFT JOIN pays ON (personnes.pays_id = pays.id)'
    );

    var $join = 'pays';

    var $validation = array(
        'nom' => 'mandatory',
        'prenom' => 'mandatory'
    );
}
