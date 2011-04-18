<?php

class CommissionsController extends xWebController {

    function defaultAction() {
        return $this->indexAction();
    }

    function indexAction() {
        $data = array(
            'title' => 'Commissions',
            'id' => 'commissions',
            'url' => '/api/commissions',
            'fields' => xView::load('commissions/extjs/fields')->render(),
            'columns' => xView::load('commissions/extjs/columns')->render()
        );
        return xView::load('common/extjs/grid', $data, $this->meta)->render();
    }

    function get() {
        // If applicable, performs a search on model fields
        if (@$this->params['query']) {
            $fields = array_keys(xModel::load('commissions')->mapping);
            foreach ($fields as $field) {
                $this->params[$field] = "%{$this->params['query']}%";
                $this->params["{$field}_comparator"] = 'LIKE';
                $this->params["{$field}_operator"] = 'OR';
            }
        }
        return xModel::load('commission', $this->params)->get();
    }

    function post() {
        return xModel::load('commission', $this->params)->post();
    }

    function put() {
        return xModel::load('commission', $this->params)->put();
    }

    function delete() {
        return xModel::load('commission', $this->params)->delete();
    }
}