<?php

class HomeController extends iaWebController {

    function defaultAction() {
        return xView::load('home/home')->render();
    }
}