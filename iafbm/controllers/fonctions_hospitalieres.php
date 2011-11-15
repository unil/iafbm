<?php

class FonctionsHospitalieresController extends iaWebController {

    var $model = 'fonction_hospitaliere';

    function indexAction() {
        $data = array(
            'title' => 'Fonctions hospitalieres',
            'id' => 'fonctions-hospitalieres',
            'model' => 'FonctionHospitaliere'
        );
        return xView::load('common/extjs/grid', $data, $this->meta)->render();
    }
}