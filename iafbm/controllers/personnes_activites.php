<?php

class PersonnesActivitesController extends iaWebController {

    var $model = 'personne_activite';

    var $query_fields = array(
        'personne_nom',
        'personne_presnom',
        'activite_abreviation',
        'activite_nom',
        'departement_nom',
        'section_nom'
    );
}