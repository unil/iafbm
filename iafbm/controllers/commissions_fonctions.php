<?php

/**
 * @package iafbm
 * @subpackage controller
 */
class CommissionsFonctionsController extends iaExtRestController {
    var $model = 'commission_fonction';
    var $allow = array('get');

    /**
     * Orders fonctions by position ascending.
     */
    function get() {
        // If no order defined, results are ordered by 'position'
        if (!isset($this->params['xorder_by']) && !isset($this->params['xorder'])) {
            $this->params['xorder_by'] = 'position';
            $this->params['xorder'] = 'ASC';
        }
        return parent::get();
    }
}