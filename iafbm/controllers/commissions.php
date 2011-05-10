<?php

class CommissionsController extends iaWebController {

    var $model = 'commission';

    function indexAction() {
        $data = array(
            'title' => 'Commissions',
            'id' => 'commissions',
            'columns' => xView::load('commissions/extjs/columns')->render(),
            'model' => 'Commission'
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
        return xView::load('commissions/detail', $data, $this->meta)->render();
    }
}