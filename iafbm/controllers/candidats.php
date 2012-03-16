<?php

class CandidatsController extends iaWebController {

    var $model = 'candidat';

    var $query_fields = array(
        'nom', 'prenom', 'pays_nom', 'pays_code', 'date_naissance', 'commission_nom'
    );
    var $query_fields_transform = array(
        'date_naissance' => 'date,date-binomial'
    );
    var $query_join = 'commission';

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