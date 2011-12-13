<?php

class PersonneActiviteModel extends iaModelMysql {

    var $table = 'personnes_activites';

    var $mapping = array(
        'id' => 'id',
        'personne_id' => 'personne_id',
        'departement_id' => 'departement_id',
        'activite_id' => 'activite_id',
        'taux_activite' => 'taux_activite',
        'date_contrat' => 'date_contrat',
        'debut_mandat' => 'debut_mandat',
        'fin_mandat' => 'fin_mandat',
    );

    var $primary = array('id');

    var $order_by = 'fin_mandat';
    var $order = 'ASC';

    var $joins = array(
        'personne' => 'LEFT JOIN personnes ON (personnes_activites.personne_id = personnes.id)',
        'activite' => 'LEFT JOIN activites ON (personnes_activites.activite_id = activites.id)',
        'departement' => 'LEFT JOIN departements ON (personnes_activites.departement_id = departements.id)'
    );

    var $join = array('personne', 'activite', 'departement');
}
