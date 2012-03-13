<?php

class PersonnesFormationsController extends iaWebController {

    var $model = 'personne_formation';

    var $query_fields = array('formation_abreviation', 'date_these', 'lieu_these', 'commentaire');

    var $sort_fields_substitutions = array(
        'formation_id' => array(
            'field' => 'formation_abreviation',
            'join' => 'formation'
        )
    );
}