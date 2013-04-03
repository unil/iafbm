<?php

/**
 * @package iafbm
 * @subpackage controller
 */
class RattachementsController extends iaExtRestController {

    var $model = 'rattachement';

    /**
     * Displays a grid of rattachements.
     */
    function indexAction() {
        $data = array(
            'title' => 'Rattachements',
            'id' => 'rattachements',
            'model' => 'Rattachement'
        );
        return xView::load('common/extjs/grid', $data, $this->meta)->render();
    }
}