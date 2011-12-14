<?php

class ActivitesController extends iaWebController {

    var $model = 'activite';
    var $allow = array('get');

    function indexAction() {
        $data = array(
            'title' => 'Activités professionnelles',
            'id' => 'activites',
            'model' => 'Activite',
            'editable' => false,
            'toolbarButtons' => array('search')
        );
        return xView::load('common/extjs/grid', $data, $this->meta)->render();
    }
}