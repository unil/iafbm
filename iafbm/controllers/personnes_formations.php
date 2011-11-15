<?php

class PersonnesFormationsController extends iaWebController {

    var $model = 'personne_formation';

    var $query_fields = array('formation_abreviation', 'date_these', 'lieu_these', 'commentaire');
}