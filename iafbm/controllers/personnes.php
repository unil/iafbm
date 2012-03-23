<?php

class PersonnesController extends iaWebController {

    var $model = 'personne';

    var $query_fields = array(
        'nom', 'prenom', 'pays_nom', 'pays_code', 'date_naissance'
    );
    var $query_fields_transform = array(
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

    /**
     * Returns geojson representation of data
     */
    function map() {
        // TODO: Set OpenLayers Layer Strategy to OpenLayers.Strategy.BBOX
        //       and query using BBOX information.
        //       Also made xModel able to issue '`field` BETWEEN x AND y' where clauses.
        // Eg: $bbox = array_map('trim', explode(',', $this->params['bbox']));

        // Retrieves personnes, adresses and pays
        $model = xController::load('personnes_adresses',
            xUtil::array_merge(
                $this->params,
                array('xjoin' => 'personne,adresse_type,adresse,pays')
            )
        );
        $model->query_fields = array(); // Make all fields queriable
        $result = $model->get();
        // Creates a GeoJSON-compliant array of features
        $features = array();
        foreach ($result['items'] as $item) {
            // Skips items without a geometry
            if (is_null(@$item['adresse_geo_x']) || is_null(@$item['adresse_geo_y'])) continue;
            $features[] = array(
                'type' => 'Feature',
                'geometry' => array(
                    'type' => 'Point',
                    'coordinates' => array($item['adresse_geo_x'], $item['adresse_geo_y'])
                ),
                'properties' => xUtil::filter_keys($item, array('adresse_geo_x', 'adresse_geo_y'), true)
            );
        }
        // Returns a GeoJSON-compliant data structure
        return array(
            'type' => 'FeatureCollection',
            'features' => $features
        );
    }

    function get() {
        $personnes = parent::get();
        // Adds '_activites' ghost field (if applicable)
        $return = xModel::load($this->model, $this->params)->return;
        if (xUtil::in_array(array('*', '_activites'), $return)) {
            foreach ($personnes['items'] as &$personne) {
                // Fetches 'Fonction' for the current 'Personne'
                $fonctions = xModel::load('personne_activite', array(
                    'personne_id' => $personne['id'],
                    'xjoin' => 'activite,activite_nom',
                    'xorder_by' => 'activite_nom_abreviation',
                    'xorder' => 'ASC'
                ))->get();
                // Creates a CSV list of 'Fonction'
                $f = array();
                foreach($fonctions as $fonction) {
                    $f[] = $fonction['activite_nom_abreviation'];
                }
                // Adds it to the resultset
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
