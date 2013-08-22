<?php

/**
 * @package iafbm
 * @subpackage controller
 */
class PersonnesFormationsController extends iaExtRestController {

    var $model = 'personne_formation';

    var $query_fields = array('formation_abreviation', 'date_these', 'lieu_these', 'commentaire');

    var $sort_fields_substitutions = array(
        'formation_id' => array(
            'field' => 'formation_abreviation',
            'join' => 'formation'
        )
    );

    /**
     * Adds 'date_these' components as ghost fields
     * (_date_these_jour, _date_these_mois, _date_these_annee).
     * @see CandidatsFormationsController::get()
     */
    function get() {
        $items = parent::get();
        // Adds '_date_these_*' ghost fields
        foreach ($items['items'] as &$item) {
            $info = date_parse($item['date_these']);
            $item['_date_these_jour'] = $info['day'];
            $item['_date_these_mois'] = $info['month'];
            $item['_date_these_annee'] = $info['year'];
            $item['_date_these_valid'] = checkdate($info['month'], $info['day'], $info['year']);
        }
        return $items;
    }

    /**
     * Stores 'date_these' according _date_these_* ghost fields values.
     * @see get()
     * @see handle_date()
     * @see CandidatsFormationsController::put()
     */
    function put() {
        $this->handle_date();
        return parent::put();
    }

    /**
     * Stores 'date_these' according '_date_these_*' ghost fields values.
     * @see get()
     * @see handle_date()
     * @see CandidatsFormationsController::put()
     */
    function post() {
        $this->handle_date();
        return parent::post();
    }

    /**
     * Manages '_date_these_*' ghost fields components:
     * - Validates date components
     * - Create 'date_these' field according '_date_these_*' ghost fields values
     * @see put()
     * @see post()
     * @see CandidatsFormationsController::handle_date()
     */
    protected function handle_date() {
        // Checks date validity
        $info = array(
            'day' => $this->params['items']['_date_these_jour'],
            'month' => $this->params['items']['_date_these_mois'],
            'year' => $this->params['items']['_date_these_annee'],
        );
        // Checks date components range
        if ($info['day'] < 0 || $info['day'] < 0 || $info['year'] < 0)
            throw new xException('Invalid date');
        // Checks date validity
        if ($info['day'] && $info['month'] && $info['year'])
            if (!checkdate($info['month'], $info['day'], $info['year']))
                throw new xException('Invalid date');
        // Injects date components into 'date_these' field
        $this->params['items']['date_these'] = "{$info['year']}-{$info['month']}-{$info['day']}";
    }
}