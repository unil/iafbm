<?php

class CommissionsController extends iaWebController {

    var $model = 'commission';

    function defaultAction() {
        return $this->indexAction();
    }

    function indexAction() {
        $data = array(
            'title' => 'Commissions',
            'id' => 'commissions',
            'url' => '/api/commissions',
            'fields' => xView::load('commissions/extjs/fields')->render(),
            'columns' => xView::load('commissions/extjs/columns')->render()
        );
        return xView::load('common/extjs/grid', $data, $this->meta)->render();
    }
}