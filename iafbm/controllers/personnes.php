<?php

class PersonnesController extends iaWebController {

    var $model = 'personne';

    var $query_fields = array('nom', 'prenom', 'pays_nom', 'pays_code');

    var $sort_fields_substitutions = array(
        'pays_id' => array(
            'field' => 'pays_nom',
            'join' => 'pays'
        )
    );

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
        );
        return xView::load('personnes/detail', $data, $this->meta)->render();
    }

    function get() {
        $personnes = parent::get();
        foreach ($personnes['items'] as &$personne) {
            // Fetches 'Fonction' for the current 'Personne'
            $fonctions = xModel::load('personne_activite', array(
                'personne_id' => $personne['id'],
                'xjoin' => 'activite,activite_nom'
            ))->get();
            // Creates a CSV list of 'Fonction'
            $f = array();
            foreach($fonctions as $fonction) {
                $f[] = $fonction['activite_nom_abreviation'];
            }
            // Adds it to the resultset
            $personne['_activites'] = implode(', ', $f);
        }
        return $personnes;
    }

    /**
     * Ensures 'nom' + 'prenom' fields begin with capitals.
     * @see transform_params()
     */
    function post() {
        $this->transform_params();
        return parent::post();
    }

    /**
     * Ensures 'nom' + 'prenom' fields begin with capitals.
     * @see transform_params()
     */
    function put() {
        $this->transform_params();
        return parent::put();
    }

    protected function transform_params() {
        foreach (array('nom', 'prenom') as $p) {
            $param = &$this->params['items'][$p];
            if (isset($param))
                $param = $this->ucnames($param);
        }
    }
    protected function ucnames($str) {
        return str_replace('- ','-',ucwords(str_replace('-','- ',$str)));
    }
}