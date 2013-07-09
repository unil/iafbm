<?php

/**
 * @package iafbm
 * @subpackage controller
 */
class DocController extends xController {

    /**
     * Displays the default doc page.
     */
    public function defaultAction() {
        return $this->indexAction();
    }

    /**
     * Displays the doc index
     */
    public function indexAction() {
        return xView::load('doc/index')->render();
    }

    /**
     * Displays the data resources documentation.
     */
    public function ressourcesAction() {
        if (!@$this->params['id']) return xView::load('doc/ressources')->render();
        else return xView::load('doc/ressource', array(
            'controller' => $this->params['id'],
            'params' => $this->params,
        ))->render();
    }
}