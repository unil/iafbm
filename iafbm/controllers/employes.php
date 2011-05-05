<?php

class EmployesController extends iaWebController {

    var $model = 'employe';

    function indexAction() {
        $data = array(
            'title' => 'Employés',
            'id' => 'employes',
            'url' => xUtil::url('api/employes'),
            'fields' => xView::load('employes/extjs/fields')->render(),
            'columns' => xView::load('employes/extjs/columns')->render(),
            'models' => array(
                xView::load('employes/extjs/model')->render(),
                xView::load('pays/extjs/model')->render()
            ),
            'model' => 'Employe'
        );
        return xView::load('common/extjs/grid', $data, $this->meta)->render();
    }

    function post() {
        $params = $this->params['items'];
        $employe = xModel::load('employe', $params);
        $personne = xModel::load('personne', $employe->foreign_fields_values('personne'));
        $t = new xTransaction();
        $t->start();
        $t->execute($employe, 'post');
        $t->execute($personne, 'post');
        $r = $t->end();
        $r['items'] = array_shift(xModel::load('employe', array('id' => $params['id']))->get());
        return $r;
    }

    function put() {
        $params = $this->params['items'];
        $employe = xModel::load('employe', $params);
        $personne = xModel::load('personne', $employe->foreign_fields_values('personne'));
        $t = new xTransaction();
        $t->start();
        $t->execute($personne, 'put');
        $employe->params['personne_id'] = $t->insertid();
        $t->execute($employe, 'put');
        $r = $t->end();
        $r['items'] = array_shift(xModel::load('employe', array('id' => $t->insertid()))->get());
        return $r;
    }

    function delete() {
        $params = $this->params;
        $employe = array_shift(xModel::load('employe', array('id'=>$params['id']))->get());
        $t = new xTransaction();
        $t->start();
        $t->execute(xModel::load('employe', $params), 'delete');
        $t->execute(xModel::load('personne', array('id'=>$employe['personne_id'])), 'delete');
        $r = $t->end();
        $r['items']['id'] = $params['id'];
        return $r;
    }
}