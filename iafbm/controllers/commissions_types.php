<?php

class CommissionsTypesController extends iaWebController {

    var $model = 'commission_type';
    var $allow = array('get');

    function indexAction() {
        $data = array(
            'title' => 'Types de commissions',
            'id' => 'commissions-types',
            'model' => 'CommissionType',
            'toolbarButtons' => array('search')
        );
        return xView::load('common/extjs/grid', $data, $this->meta)->render();
    }
}