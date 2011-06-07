<?php

class CommissionsController extends iaWebController {

    var $model = 'commission';

    function indexAction() {
        $data = array(
            'title' => 'Gestion des commissions',
            'id' => 'commissions',
            'model' => 'Commission'
        );
        return xView::load('common/extjs/grid', $data, $this->meta)->render();
    }

    function detailAction() {
        $data = array(
            'id' => $this->params['id'],
/*
            'title' => 'Commissions',
            'id' => 'commissions',
            'url' => xUtil::url('api/commissions'),
            'fields' => xView::load('commissions/extjs4/fields')->render(),
            'columns' => xView::load('commissions/extjs4/columns')->render()
*/
        );
        return xView::load('commissions/detail', $data, $this->meta)->render();
    }

    function get() {
        $commissions = parent::get();
        // Adds 'president' ghost-field
        foreach ($commissions['items'] as &$commission) {
            $president = array_shift(xModel::load(
                'commission-membre',
                array(
                    'commission_id' => $commission['id'],
                    'commission-fonction_id' => 1
                )
            )->get());
            $commission['_president'] = ($president) ?
                "{$president['personne_prenom']} {$president['personne_nom']}" :
                '-';
        }
        return $commissions;
    }

    /**
     * Depending on the type of the commission,
     * different types of database entities have to be created.
     */
    function put() {
        if (isset($this->params['id'])) return $this->post();
        $params = $this->params['items'];
        $t = new xTransaction();
        $t->start();
        // Inserts the commission model
        $t->execute(xModel::load('commission', $params), 'put');
        $insertid = $t->insertid();
        // Inserts related items
        switch (@$params['commission-type_id']) {
            case 1:
                $items = array(
                    xModel::load('commission-creation', array('commission_id'=>$insertid)),
                    xModel::load('commission-candidat-commentaire', array('commission_id'=>$insertid))
                );
                break;
            default:
                throw new xException('Unknown commission type', 500);
        }
        foreach ($items as $item) $t->execute($item, 'put');
        $r = $t->end();
        $r['items'] = array_shift(xModel::load('commission', array('id' => $insertid))->get());
        return $r;
    }

    function delete() {
        if (!in_array('delete', $this->allow)) throw new xException("Method not allowed", 403);
        $t = new xTransaction();
        $t->start();
        $params = array('commission_id' => $this->params['id']);
        $t->execute(xModel::load('commission-creation', $params), 'delete');
        $t->execute(xModel::load('commission-membre', $params), 'delete');
        $t->execute(xModel::load('commission-candidat-commentaire', $params), 'delete');
        $t->execute(xModel::load('commission-candidat', $params), 'delete');
        $t->execute(xModel::load('commission', $this->params), 'delete');
        $r = $t->end();
    }
}