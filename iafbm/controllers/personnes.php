<?php

class PersonnesController extends xWebController {

    function defaultAction() {
        return $this->indexAction();
    }

    function indexAction() {
        $data = array(
            'title' => 'Personnes',
            'id' => 'personnes',
            'url' => '/api/personnes',
            'fields' => xView::load('personnes/extjs/fields')->render(),
            'columns' => xView::load('personnes/extjs/columns')->render()
        );
        return xView::load('common/extjs/grid', $data, $this->meta)->render();
    }

    function get() {
        return xModel::load('personne', $this->params)->get();
    }

    function post() {
        return xModel::load('personne', $this->params)->post();
    }

    function put() {
        return xModel::load('personne', $this->params)->put();
    }

    function delete() {
        return xModel::load('personne', $this->params)->delete();
    }
}