<?php

class PersonnesController extends iaWebController {

    var $model = 'personne';

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
}