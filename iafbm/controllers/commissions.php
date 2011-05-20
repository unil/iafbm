<?php

class CommissionsController extends iaWebController {

    var $model = 'commission';

    function indexAction() {
        $data = array(
            'title' => 'Commissions',
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

    /**
     * Depending on the type of the commission,
     * different types of database entities have to be created.
     */
    function put() {
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
                    xModel::load('commission-creation', array('commission_id'=>$insertid))
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
}