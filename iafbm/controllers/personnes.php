<?php

class PersonnesController extends iaWebController {

    var $model = 'personne';

    var $query_fields = array(
        'nom', 'prenom', 'pays_nom', 'pays_code', 'date_naissance'
    );
    var $query_transform = array(
        'date_naissance' => 'date,date-binomial'
    );
    var $query_join = 'pays';

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
        // Adds '_activites' ghost field (if applicable)
        $return = xModel::load($this->model, $this->params)->return;
        if (xUtil::in_array(array('*', '_activites'), $return)) {
            // Fetches 'activites' for each 'personne' in result set
            $ids = array();
            foreach ($personnes['items'] as &$personne) $ids[] = $personne['id'];
            $fonctions = xModel::load('personne_activite', array(
                'personne_id' => $ids,
                'xjoin' => 'activite,activite_nom',
                'xorder_by' => 'activite_nom_abreviation',
                'xorder' => 'ASC'
            ))->get();
            // Applies '_activites' ghost field
            foreach ($personnes['items'] as &$personne) {
                $f = array();
                foreach ($fonctions as $fonction) {
                    if ($fonction['personne_id'] == $personne['id'])
                        $f[] = $fonction['activite_nom_abreviation'];
                }
                // Creates a CSV list
                $personne['_activites'] = implode(', ', $f);
            }
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