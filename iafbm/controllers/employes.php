<?php

class EmployesController extends xWebController {

    function defaultAction() {
        return $this->indexAction();
    }

    function indexAction() {
        $data = array(
            'title' => 'Employés',
            'id' => 'employes',
            'url' => '/api/employes',
            'fields' => xView::load('employes/extjs/fields')->render(),
            'columns' => xView::load('employes/extjs/columns')->render()
        );
        return xView::load('common/extjs/grid', $data, $this->meta)->render();
    }

    function get() {
        return xModel::load('employe', $this->params)->get();
    }

    function post() {
        $employe = xModel::load('employe', $this->params);
        $personne = xModel::load('personne', $employe->foreign_fields_values('personne'));
        $t = new xTransaction();
        $t->start();
        $t->execute($employe, 'post');
        $t->execute($personne, 'post');
        $t->end();
        return xUtil::array_merge(
            array('xsuccess' => true),
            array_shift(xModel::load('employe', array('id' => $this->params['id']))->get())
        );
    }

    function put() {
        $employe = xModel::load('employe', $this->params);
        $personne = xModel::load('personne', $employe->foreign_fields_values('personne'));
        $t = new xTransaction();
        $t->start();
        $t->execute($personne, 'put');
        $employe->params['personne_id'] = $t->insertid();
        $t->execute($employe, 'put');
        $t->end();
        return xUtil::array_merge(
            array('xsuccess' => true),
            array_shift(xModel::load('employe', array('id' => $t->insertid()))->get())
        );
    }

    function delete() {
        $employe = array_shift(xModel::load('employe', array('id'=>$this->params['id']))->get());
        $t = new xTransaction();
        $t->start();
        $t->execute(xModel::load('employe', $this->params), 'delete');
        $t->execute(xModel::load('personne', array('id'=>$employe['personne_id'])), 'delete');
        return array_merge($t->end(), array('id' => $this->params['id']));
    }
}