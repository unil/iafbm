<?php

class PersonnesController extends xWebController {

    function defaultAction() {
        return $this->indexAction();
    }

    function indexAction() {
        return xView::load('personnes/index')->render();
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