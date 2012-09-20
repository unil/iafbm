<?php

class EtudiantsController extends iaExtRestController {

    var $model = 'etudiant';

    function indexAction() {
        $grid = array(
            'title' => 'Etudiants',
            'id' => 'etudiants',
            'url' => xUtil::url('/api/etudiants'),
            'fields' => xView::load('etudiants/extjs/fields')->render(),
            'columns' => xView::load('etudiants/extjs/columns')->render(),
            'models' => array(
                xView::load('etudiants/extjs/model')->render(),
                xView::load('pays/extjs/model')->render()
            ),
            'model' => 'Etudiant'
        );
        return xView::load('common/extjs/grid', $grid, $this->meta)->render();
    }

    function post() {
        $params = $this->params['items'];
        $etudiant = xModel::load('etudiant', $params);
        $personne = xModel::load('personne', $etudiant->foreign_fields_values('personne'));
        $t = new xTransaction();
        $t->start();
        $t->execute($etudiant, 'post');
        $t->execute($personne, 'post');
        $r = $t->end();
        $r['items'] = array_shift(xModel::load('employe', array('id' => $params['id']))->get());
        return $r;
    }

    function put() {
        $params = $this->params['items'];
        $etudiant = xModel::load('etudiant', $params);
        $personne = xModel::load('personne', $etudiant->foreign_fields_values('personne'));
        $t = new xTransaction();
        $t->start();
        $t->execute($personne, 'put');
        $etudiant->params['personne_id'] = $t->insertid();
        $t->execute($etudiant, 'put');
        $r = $t->end();
        $r['items'] = array_shift(xModel::load('employe', array('id' => $t->insertid()))->get());
        return $r;
    }

    function delete() {
        $params = $this->params;
        $etudiant = array_shift(xModel::load('etudiant', array('id'=>$params['id']))->get());
        $t = new xTransaction();
        $t->start();
        $t->execute(xModel::load('etudiant', $params), 'delete');
        $t->execute(xModel::load('personne', array('id'=>$etudiant['personne_id'])), 'delete');
        $r = $t->end();
        $r['items']['id'] = $params['id'];
        return $r;
    }
}