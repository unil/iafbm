<?php

class EtudiantsController extends xWebController {

    function defaultAction() {
        return $this->indexAction();
    }

    function indexAction() {
        $grid = array(
            'title' => 'Etudiants',
            'id' => 'etudiants',
            'url' => '/api/etudiants',
            'fields' => xView::load('etudiants/extjs/fields')->render(),
            'columns' => xView::load('etudiants/extjs/columns')->render()
        );
        return xView::load('common/extjs/grid', $grid, $this->meta)->render();
    }

    function get() {
        return xModel::load('etudiant', $this->params)->get();
    }

    function post() {
        $etudiant = xModel::load('etudiant', $this->params);
        $personne = xModel::load('personne', $etudiant->foreign_fields_values('personne'));
        $t = new xTransaction();
        $t->start();
        $t->execute($etudiant, 'post');
        $t->execute($personne, 'post');
        $t->end();
        return xUtil::array_merge(
            array('xsuccess' => true),
            array_shift(xModel::load('etudiant', array('id' => $this->params['id']))->get())
        );
    }

    function put() {
        $etudiant = xModel::load('etudiant', $this->params);
        $personne = xModel::load('personne', $etudiant->foreign_fields_values('personne'));
        $t = new xTransaction();
        $t->start();
        $t->execute($personne, 'put');
        $etudiant->params['personne_id'] = $t->insertid();
        $t->execute($etudiant, 'put');
        $t->end();
        return xUtil::array_merge(
            array('xsuccess' => true),
            array_shift(xModel::load('etudiant', array('id' => $t->insertid()))->get())
        );
    }

    function delete() {
        $etudiant = array_shift(xModel::load('etudiant', array('id'=>$this->params['id']))->get());
        $t = new xTransaction();
        $t->start();
        $t->execute(xModel::load('etudiant', $this->params), 'delete');
        $t->execute(xModel::load('personne', array('id'=>$etudiant['personne_id'])), 'delete');
        return array_merge($t->end(), array('id' => $this->params['id']));
    }
}