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
    var $query_exclude_fields = array();

    /**
     * @todo
     * Return true if the method is allowed.
     * Checks for:
     * - $this->allow rights
     * - authenticated role
     */
    function is_allowed() {
        /* TODO
        if (!in_array($this->http['method'], $this->allow))
            throw new xException('Method not allowed', 403);
        if (false)
            throw new xException('Insufficent privileges', 403);
        */
    }

    function defaultAction() {
        if (!isset($this->params['id'])) {
            if (method_exists($this, 'indexAction')) return $this->indexAction();
        } else {
            if (method_exists($this, 'detailAction')) return $this->detailAction();
        }
        throw new xException('Not found', 404);
    }

    function get() {
        if (!in_array('get', $this->allow)) throw new xException("Method not allowed", 403);
        // Creates parameter for model instance
        $params = $this->params;
        if (strlen(@$this->params['query']) > 0) {
            $fields = array_merge(
                array_keys(xModel::load($this->model)->mapping),
                array_keys(xModel::load($this->model)->foreign_mapping())
            );
            foreach ($fields as $field) {
                if (in_array($field, $this->query_exclude_fields)) continue;
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
        if (!isset($this->params['id'])) return $this->put();
        if (!in_array('post', $this->allow)) throw new xException("Method not allowed", 403);
        if (!isset($this->params['items'])) throw new xException('No items provided', 400);
        $r = xModel::load($this->model, $this->params['items'])->post();
        $r['items'] = array_shift(xModel::load($this->model, array('id'=>$this->params['items']['id']))->get());
        return $r;
    }

    function put() {
        if (isset($this->params['id'])) return $this->post();
        if (!in_array('put', $this->allow)) throw new xException("Method not allowed", 403);
        if (!isset($this->params['items'])) throw new xException('No items provided', 400);
        $r = xModel::load($this->model, $this->params['items'])->put();
        $r['items'] = array_shift(xModel::load($this->model, array('id'=>$r['xinsertid']))->get());
        return $r;
    }

    function delete() {
        if (!in_array('delete', $this->allow)) throw new xException("Method not allowed", 403);
        return xModel::load($this->model, array('id'=>$this->params['id']))->delete();
    }
}