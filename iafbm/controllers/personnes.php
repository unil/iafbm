<?php

class PersonnesController extends iaWebController {

    var $model = 'personne';

    var $query_exclude_fields = array('pays_nom_en');

    function indexAction() {
        $data = array(
            'title' => 'Personnes',
            'id' => 'personnes',
            'url' => xUtil::url('api/personnes'),
            'fields' => xView::load('personnes/extjs/fields')->render(),
            'columns' => xView::load('personnes/extjs/columns')->render(),
            'models' => array(
                xView::load('personnes/extjs/model')->render(),
                xView::load('pays/extjs/model')->render()
            ),
            'model' => 'Personne'

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
        return xView::load('personnes/detail', $data, $this->meta)->render();
    }
}