<?php

class MapController extends xWebController {

    function defaultAction() {
        return xView::load('common/map/map', array(
            'dom_id' => 'map',
            'width' => '',
            'height' => 500
        ), $this->meta)->render();
    }

}