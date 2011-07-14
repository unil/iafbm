<?php

class CandidatsController extends iaWebController {

    var $model = 'candidat';

    function indexAction() {
        $data = array(
            'title' => 'Candidats',
            'id' => 'candidats',
            'model' => 'Candidat',
            'toolbarButtons' => array()
        );
        return xView::load('common/extjs/grid', $data, $this->meta)->render();
    }

    function detailAction() {
        $data = array(
            'id' => $this->params['id'],
/*
            'title' => 'Commissions',
            'id' => 'commissions',
            'url' => xUtil::url('api/commissions'),
            'fields' => xView::load('commissions/extjs4/fields')->render(),
            'columns' => xView::load('commissions/extjs4/columns')->render()
*/
        );
        return xView::load('candidats/detail', $data, $this->meta)->render();
    }
}