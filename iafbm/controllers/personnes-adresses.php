<?php

class PersonnesAdressesController extends iaWebController {

    var $model = 'personne-adresse';

    function post() {
        $params = $this->params['items'];
        $personne_adresse = xModel::load('personne-adresse', $params);
        $adresse = xModel::load('adresse', $personne_adresse->foreign_fields_values('adresse'));
        $t = new xTransaction();
        $t->start();
        $t->execute($personne_adresse, 'post');
        $t->execute($adresse, 'post');
        $r = $t->end();
        $r['items'] = array_shift(xModel::load($this->model, array('id' => $params['id']))->get());
        return $r;
    }

    function put() {
        $params = $this->params['items'];
        $personne_adresse = xModel::load('personne-adresse', $params);
        $adresse = xModel::load('adresse', $personne_adresse->foreign_fields_values('adresse'));
        $t = new xTransaction();
        $t->start();
        $t->execute($adresse, 'put');
        $personne_adresse->params['adresse_id'] = $t->insertid();
        $t->execute($personne_adresse, 'put');
        $r = $t->end();
        $r['items'] = array_shift(xModel::load($this->model, array('id' => $t->insertid()))->get());
        return $r;
    }

    function delete() {
        $params = $this->params;
        $personne_adresse = array_shift(xModel::load($this->model, array('id'=>$params['id']))->get());
        $t = new xTransaction();
        $t->start();
        $t->execute(xModel::load('personne-adresse', $params), 'delete');
        $adresse_id = $personne_adresse['adresse_id'];
        $t->execute(xModel::load('adresse', array('id'=>$adresse_id)), 'delete');
        $r = $t->end();
        $r['items']['id'] = $params['id'];
        return $r;
    }
}