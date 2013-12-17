<?php

class PersonneEvaluationModel extends iaModelMysql {
    
    var $table = 'personnes_evaluations';

    var $mapping = array(
        'id' => 'id',
        'personne_id' => 'personne_id',
        'evaluation_id' => 'evaluation_id'
    );

    //var $primary = array('id');

    /*var $joins = array(
        'evaluation_rapport' => 'LEFT JOIN evaluation_rapport ON (personnes_evaluations.evaluation_id = evaluation_rapport.id)',
        'personne' => 'LEFT JOIN personne ON (personnes_evaluations.personne_id = personne.id)'
    );*/

    //var $join = array('evaluation_rapport, personne');
}
