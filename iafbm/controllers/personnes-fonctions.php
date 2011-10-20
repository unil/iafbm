<?php

class PersonnesFonctionsController extends iaWebController {

    var $model = 'personne-fonction';

    var $query_fields = array(
        'titre-academique_abreviation',
        'titre-academique_nom',
        'departement_nom',
        'fonction-hospitaliere_nom'
    );
}