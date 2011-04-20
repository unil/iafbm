<?php

/**
 * Project specific xWebController
 */
class iaWebController extends xWebController {

    var $model = null;

    /**
     * Allowed CRUD operations.
     * Possible values: 'get', 'post', 'put', 'delete'
     * @var array
     */
    var $allow = array('get', 'post', 'put', 'delete');

    /**
     * Excluded fields for model parameters creation on query.
     * @see iaWebController::get()
     */
    var $query_exclude = array();

    function get() {
        if (!in_array('get', $this->allow)) throw new xException("Method not allowed", 403);
        // Creates parameter for model instance
        $params = $this->params;
        if (@$this->params['query']) {
            $fields = array_merge(
                array_keys(xModel::load($this->model)->mapping),
                array_keys(xModel::load($this->model)->foreign_mapping())
            );
            foreach ($fields as $field) {
                if (in_array($field, $this->query_exclude)) continue;
                $params[$field] = "%{$this->params['query']}%";
                $params["{$field}_comparator"] = 'LIKE';
                $params["{$field}_operator"] = 'OR';
            }
        }
        // Creates extjs compatible result
        return array(
            'xcount' => xModel::load($this->model, xUtil::filter_keys($params, array('xoffset', 'xlimit'), true))->count(),
            'items' => xModel::load($this->model, $params)->get()
        );
    }

    function post() {
        if (!in_array('post', $this->allow)) throw new xException("Method not allowed", 403);
        $r = xModel::load($this->model, $this->params['items'])->post();
        $r['items'] = array_shift(xModel::load($this->model, array('id'=>$this->params['items']['id']))->get());
        return $r;
    }

    function put() {
        if (!in_array('put', $this->allow)) throw new xException("Method not allowed", 403);
        $r = xModel::load($this->model, $this->params['items'])->put();
        $r['items'] = array_shift(xModel::load($this->model, array('id'=>$r['xinsertid']))->get());
        return $r;
    }

    function delete() {
        if (!in_array('delete', $this->allow)) throw new xException("Method not allowed", 403);
        return xModel::load($this->model, $this->params['id'])->delete();
    }
}