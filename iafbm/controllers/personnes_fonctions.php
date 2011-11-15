<?php

class PersonnesFonctionsController extends iaWebController {

    var $model = 'personne_fonction';

    var $query_fields = array(
        'titre_academique_abreviation',
        'titre_academique_nom',
        'departement_nom',
        'fonction_hospitaliere_nom'
    );
}