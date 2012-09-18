<?php

require_once('commissions.php');

class CandidatsController extends AbstractCommissionController {

    var $model = 'candidat';

    var $query_fields = array(
        'nom', 'prenom', 'pays_nom', 'pays_code', 'date_naissance', 'commission_nom'
    );
    var $query_fields_transform = array(
        'date_naissance' => 'date,date-binomial'
    );
    var $query_join = 'commission';

    var $sort_fields_substitutions = array(
        'genre_id' => array(
            'field' => 'genre_genre',
            'join' => 'genre'
        )
    );

    function indexAction() {
        return xView::load('candidats/list', array(), $this->meta)->render();
    }

    function detailAction() {
        $data = array(
            'id' => $this->params['id'],
        );
        return xView::load('candidats/detail', $data, $this->meta)->render();
    }

    /**
     * Adds default adress ghost fields
     */
    function get() {
        $result = parent::get();
        // Adds ghost fields
        foreach ($result['items'] as &$item) {
            // Creates default 'adresse' fields
            $default = $item['adresse_defaut'];
            $fields = array('adresse_#', 'npa_#', 'lieu_#', 'pays_#_id', 'telephone_#_countrycode', 'telephone_#', 'email_#');
            foreach ($fields as $field) {
                $field_source = str_replace('#', $default, $field);
                $field_dest = str_replace('#', 'defaut', $field);
                $item["_{$field_dest}"] = @$item[$field_source];
            }
            // Creates 'primo loco' field
            $commission_travail = xModel::load('commission_travail', array(
                'commission_id' => $item['commission_id']
            ))->get(0);
            $item['_primo_loco'] = ($item['id'] == $commission_travail['primo_loco']);
        }
        return $result;
    }

    /**
     * Ensures 'nom' + 'prenom' fields begin with capitals.
     * @see PersonnesController
     * @see transform_params()
     */
    function post() {
        $this->transform_params();
        return parent::post();
    }

    /**
     * Ensures 'nom' + 'prenom' fields begin with capitals.
     * @see PersonnesController
     * @see transform_params()
     */
    function put() {
        $this->transform_params();
        return parent::put();
    }

    /**
     * Deletes 'candidat':
     * - Cascades delete on 'candidat_formation'
     * - Nulls 'commission_travail.*_loco'
     * - Nulls 'commission_finalisation.candidat_id'
     */
    function delete() {
        if (!in_array('delete', $this->allow)) throw new xException("Method not allowed", 403);
        $t = new xTransaction();
        $t->start();
        // Determines candidat & commission id
        $id_candidat = $this->params['id'];
        $id_commission = xModel::load('candidat', array('id' => $id_candidat))->get(0);
        $id_commission = $id_commission['commission_id'];
        // Soft-deletes foreign 'candidat_formation' entity/ies
        $ids = $this->_get_ids(
            xModel::load('candidat_formation', array('candidat_id'=>$id_candidat))
        );
        foreach ($ids as $id) $t->execute(xModel::load('candidat_formation', array(
            'id' => $id
        )), 'delete');
        // Nulls foreign 'commission_travail' reference
        $t->execute(xModel::load('commission_travail', array(
            'id' => $id_commission,
            'primo_loco' => null,
            'secondo_loco' => null,
            'tertio_loco' => null
        )), 'post');
        // Nulls foreign 'commission_travail' reference
        $t->execute(xModel::load('commission_finalisation', array(
            'id' => $id_commission,
            'candidat_id' => null
        )), 'post');
        // Soft-deletes actual entity (soft-delete)
        $t->execute(xModel::load('candidat', array(
            'id' => $id_candidat,
            'actif' => 0
        )), 'post');
        return $t->end();
    }
    protected function _get_ids(xModel $model) {
        $r = $model->get();
        $ids = array();
        foreach ($r as $item) $ids[] = $item[$model->primary()];
        return $ids;
    }

    /**
     * @see PersonnesController
     */
    protected function transform_params() {
        foreach (array('nom', 'prenom') as $p) {
            $param = &$this->params['items'][$p];
            if (isset($param))
                $param = $this->ucnames($param);
        }
    }
    /**
     * @see PersonnesController
     */
    protected function ucnames($str) {
        return str_replace('- ','-',ucwords(str_replace('-','- ',$str)));
    }
}