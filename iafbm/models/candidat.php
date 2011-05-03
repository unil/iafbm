<?php

class CandidatModel extends xModelMysql {

    var $table = 'commissions_candidats';

    var $mapping = array(
        'id' => 'id',
        'commission_id' => 'commission_id',
        'personne_id' => 'personne_id',
        'actif' => 'actif',
        'created' => 'created',
        'modified' => 'modified'
    );

    var $primary = array('id');

    var $joins = array(
        'personne' => 'LEFT JOIN personnes ON (commissions_candidats.personne_id = personnes.id)',
        'commission' => 'LEFT JOIN commissions ON (commissions_candidats.commission_id = commissions.id)'
    );

    var $join = array('personne', 'commission');

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
