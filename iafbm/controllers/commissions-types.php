<?php

class CommissionsTypesController extends iaWebController {

    var $model = 'commission-type';
    var $allow = array('get');

    function indexAction() {
        $data = array(
            'title' => 'Types de commissions',
            'id' => 'commissions-types',
            'model' => 'CommissionType'
        );
        return xView::load('common/extjs/grid', $data, $this->meta)->render();
    }
}