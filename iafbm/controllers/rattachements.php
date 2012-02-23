<?php

class RattachementsController extends iaWebController {

    var $model = 'rattachement';

    function indexAction() {
        $data = array(
            'title' => 'Rattachements',
            'id' => 'rattachements',
            'model' => 'Rattachement'
        );
        return xView::load('common/extjs/grid', $data, $this->meta)->render();
    }
}