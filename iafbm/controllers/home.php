<?php

class HomeController extends iaWebController {

    function __construct($params=array()) {
        parent::__construct($params);
        $this->add_meta('navigation/highlight', 'home');
    }

    function defaultAction() {
        return xView::load('home/home')->render();
    }
}