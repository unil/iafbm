<?php

class PutzController extends iaExtRestController {

    function personnesActivitesAction() {
        $fields = array(
            'personne_id' => null,
            'personne_prenom' => 'Prénom',
            'personne_nom' => 'Nom',
            'section_code' => 'Section',
            'rattachement_abreviation' => 'Rattachement',
            'activite_type_nom' => "Type d'activité",
            'activite_nom_abreviation' => 'Activite',
            'fin' => 'Echéance'
        );
        $activites = xModel::load('personne_activite', array(
            'en_vigueur' => true,
            'fin' => xUtil::ustime(),
            'fin_comparator' => '<=',
            'xreturn' => array_keys($fields),
            'xorder_by' => 'fin',
            'xorder' => 'ASC'
        ))->get();
        return xView::load('putz/personnes-activites', array(
            'fields' => $fields,
            'activites' => $activites
        ));
    }
}