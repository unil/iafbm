<?php

class DocController extends xController {

    public function defaultAction() {
        return $this->indexAction();
    }

    public function indexAction() {
        return xView::load('doc/index')->render();
    }

    public function ressourcesAction() {
        if (!@$this->params['id']) return xView::load('doc/ressources')->render();
        else return xView::load('doc/ressource', array(
            'controller' => $this->params['id'],
            'params' => $this->params,
        ))->render();
    }
}