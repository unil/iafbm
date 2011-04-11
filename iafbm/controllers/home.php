<?php

class HomeController extends xWebController {

    function init() {
        $this->add_meta('navigation/highlight', 'home');
    }

    function defaultAction() {
        return xView::load('home/home')->render();
    }
}