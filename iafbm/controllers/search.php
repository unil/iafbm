<?php

class SearchController extends iaWebController {

    function defaultAction() {
        return xView::load('search/detail')->render();
    }
}