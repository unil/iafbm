<?php

class PersonnesController extends iaWebController {

    var $model = 'personne';

    var $query_fields = array('nom', 'prenom', 'pays_nom', 'pays_code');

    function indexAction() {
        $data = array(
            'title' => 'Personnes',
            'id' => 'personnes',
            'model' => 'Personne'
        );
        return xView::load('common/extjs/grid', $data, $this->meta)->render();
    }

    function detailAction() {
        $data = array(
            'id' => $this->params['id'],
/*
            'title' => 'Commissions',
            'id' => 'commissions',
            'url' => xUtil::url('api/commissions'),
            'fields' => xView::load('commissions/extjs4/fields')->render(),
            'columns' => xView::load('commissions/extjs4/columns')->render()
*/
        );
        return xView::load('personnes/detail', $data, $this->meta)->render();
    }

    function get() {
        $personnes = parent::get();
        foreach ($personnes['items'] as &$personne) {
            // Fetches 'Fonction' for the current 'Personne'
            $fonctions = xModel::load('personne_fonction', array(
                'personne_id' => $personne['id'],
                'xjoin' => 'titre_academique'
            ))->get();
            // Creates a CSV list of 'Fonction'
            $f = array();
            foreach($fonctions as $function) {
                $f[] = $function['titre_academique_abreviation'];
            }
            // Adds it to the resultset
            $personne['_fonctions'] = implode(', ', $f);
        }
        return $personnes;
    }
}