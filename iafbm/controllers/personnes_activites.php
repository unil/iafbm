<?php

class PersonnesActivitesController extends iaExtRestController {

    var $model = 'personne_activite';

    var $query_fields = array(
        'personne_nom',
        'personne_presnom',
        'activite_abreviation',
        'activite_nom',
        'departement_nom',
        'section_nom'
    );

    var $sort_fields_substitutions = array(
        'activite_type_id' => array(
            'field' => 'activite_type_nom',
            'join' => 'activite,activite_type'
        ),
        'activite_id' => array(
            'field' => 'activite_nom_nom',
            'join' => 'activite,activite_nom'
        ),
        'rattachement_id' => array(
            'field' => 'rattachement_nom',
            'join' => 'rattachement'
        )
    );
    
    /**
     * Add ghost field
     */
    function get() {
        $results = parent::get();
        
        foreach($results['items'] as &$pa) {
            $pa['_nomPrenom'] = $pa['personne_nom'].' '.$pa['personne_prenom'];
            
        }
        
        return $results;
    }
}