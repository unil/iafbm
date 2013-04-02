<?php

class HomeController extends xWebController {

    function init() {
        $this->add_meta('navigation/highlight', 'home');
    }

    function defaultAction() {
        $data['feed'] = xController::load('feed')->defaultAction();
        return xView::load('home/home', $data)->render();
    }
}