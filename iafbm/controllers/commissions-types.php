<?php

class CommissionsTypesController extends iaWebController {

    var $model = 'commission-type';

    function indexAction() {
        $data = array(
            'title' => 'Types de commissions',
            'id' => 'commissions-types',
            'url' => xUtil::url('api/commissions-types'),
            'fields' => xView::load('commissions-types/extjs/fields')->render(),
            'columns' => xView::load('commissions-types/extjs/columns')->render(),
            'models' => xView::load('commissions-types/extjs/model')->render(),
            'model' => 'CommissionType'
        );
        return xView::load('common/extjs/grid', $data, $this->meta)->render();
    }
}