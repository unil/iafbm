<?php

/**
 * @package iafbm
 * @subpackage controller
 */
class HomeController extends xWebController {

    function init() {
        $this->add_meta('navigation/highlight', 'home');
    }

    /**
     * Displays the application homepage.
     */
    function defaultAction() {
        return xView::load('home/home')->render();
    }
}