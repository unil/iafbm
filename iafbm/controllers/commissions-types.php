<?php

class CommissionsTypesController extends iaWebController {

    var $model = 'commission-type';

    function defaultAction() {
        return $this->indexAction();
    }

    function indexAction() {
        $data = array(
            'title' => 'Types de commissions',
            'id' => 'commissions-types',
            'url' => '/api/commissions-types',
            'fields' => xView::load('commissions-types/extjs/fields')->render(),
            'columns' => xView::load('commissions-types/extjs/columns')->render()
        );
        return xView::load('common/extjs/grid', $data, $this->meta)->render();
    }
}