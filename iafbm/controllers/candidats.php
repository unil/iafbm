<?php

class CandidatsController extends iaWebController {

    var $model = 'candidat';

    function indexAction() {
        return xView::load('candidats/list', array(), $this->meta)->render();
    }

    function detailAction() {
        $data = array(
            'id' => $this->params['id'],
        );
        return xView::load('candidats/detail', $data, $this->meta)->render();
    }
}