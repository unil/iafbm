<?php

class MembreModel extends xModelMysql {

    var $table = 'commissions_membres';

    var $mapping = array(
        'id' => 'id',
        'personne_id' => 'personne_id',
        'fonction_id' => 'commission_fonction_id',
        'commission_id' => 'commission_id',
        'actif' => 'actif',
        'created' => 'created',
        'modified' => 'modified'
    );

    var $primary = array('id');

    var $joins = array(
        'personne' => 'LEFT JOIN personnes ON (commissions_membres.personne_id = personnes.id)',
        'commission-fonction' => 'LEFT JOIN commissions_fonctions ON (commissions_membres.commission_fonction_id = commissions_fonctions.id)',
        'commission' => 'LEFT JOIN commissions ON (commissions_membres.commission_id = commissions.id)'
    );

    var $join = array('personne', 'commission-fonction', 'commission');

    var $validation = array(
    );
}
