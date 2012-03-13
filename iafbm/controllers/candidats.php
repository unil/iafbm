<?php

class CandidatsController extends iaWebController {

    var $model = 'candidat';

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
}