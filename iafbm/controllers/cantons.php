<?php

class CantonsController extends xWebController {

    function get() {
        $params = array(
            'nom' => @"{$this->params['query']}%",
            'nom_comparator' => 'LIKE'
        );
        return xModel::load('canton', $params)->get();
    }
}