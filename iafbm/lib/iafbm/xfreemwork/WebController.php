<?php

/**
 * Project specific xWebController
 */
class iaWebController extends xWebController {

    var $model = null;

    /**
     * Allowed CRUD operations.
     * Possible values: 'get', 'post', 'put', 'delete'
     * @see iaWebController::get()
     * @see iaWebController::post()
     * @see iaWebController::put()
     * @see iaWebController::delete()
     * @var array
     */
    var $allow = array('get', 'post', 'put', 'delete');

    /**
     * Excluded fields for model parameters creation on query.
     * Eg. when the query parameter is provided with a GET method.
     * @see iaWebController::get()
     */
    var $query_fields = array();

    /**
     * @todo
     * Return true if the method is allowed.
     * Checks for:
     * - $this->allow rights
     * - authenticated role
     */
    function is_allowed() {
        throw new xException('Not implemented', 501);
        /* TODO
        if (!in_array($this->http['method'], $this->allow))
            throw new xException('Method not allowed', 403);
        if (false)
            throw new xException('Insufficent privileges', 403);
        */
    }

    /**
     * Manages action redirection
     * according the received params and the available controller actions.
     */
    function defaultAction() {
        if (!isset($this->params['id'])) {
            if (method_exists($this, 'indexAction')) return $this->indexAction();
        } else {
            if (method_exists($this, 'detailAction')) return $this->detailAction();
        }
        throw new xException('Not found', 404);
    }

    /**
     * API Method.
     * Generic get method for API calls.
     * @return array An ExtJS compatible resultset structure.
     */
    function get() {
        if (!in_array('get', $this->allow)) throw new xException("Method not allowed", 403);
        // Creates parameter for model instance
        $params = $this->params;
        if (strlen(@$params['query']) > 0) {
            $fields = array_merge(
                array_keys(xModel::load($this->model)->mapping),
                array_keys(xModel::load($this->model)->foreign_mapping())
            );
            // TODO:
            // - group existing fields in $this->params as AND group,
            // - group query fields as AND group with OR elements
            // Eg: AND name1=1 AND name2=2 AND (query1=abc OR query2=abc)
            //
            // Requirement: add grouping capability in xModel
            //
            // Adds (specified if applicable) model fields
            foreach ($fields as $field) {
                // Skips model field if $this->query_field exists but $field not in list
                if ($this->query_fields && !in_array($field, $this->query_fields)) continue;
                // Adds model field
                $params[$field] = "%{$this->params['query']}%";
                $params["{$field}_comparator"] = 'LIKE';
                $params["{$field}_operator"] = 'OR';
            }
            // Removes query param
            unset($params['query']);
        }
        // Creates extjs compatible result
        return array(
            'xcount' => xModel::load($this->model, xUtil::filter_keys($params, array('xoffset', 'xlimit'), true))->count(),
            'items' => xModel::load($this->model, $params)->get()
        );
    }

    /**
     * API Method.
     * Generic post method for API calls.
     * @return array An ExtJS compatible resultset structure.
     */
    function post() {
        // Redirects to the put method if no id is provided
        if (!isset($this->params['id'])) return $this->put();
        // Checks if method is allowed
        if (!in_array('post', $this->allow)) throw new xException("Method not allowed", 403);
        // Checks provided parameters
        if (!isset($this->params['items'])) throw new xException('No items provided', 400);
        // Database action
        $r = xModel::load($this->model, $this->params['items'])->post();
        // Result
        $r['items'] = array_shift(xModel::load($this->model, array('id'=>$this->params['items']['id']))->get());
        return $r;
    }

    /**
     * API Method.
     * Generic put method for API calls.
     * @return array An ExtJS compatible resultset structure.
     */
    function put() {
        // Redirects to the post method if no id is provided
        if (isset($this->params['id'])) return $this->post();
        // Checks if method is allowed
        if (!in_array('put', $this->allow)) throw new xException("Method not allowed", 403);
        // Checks provided parameters
        if (!isset($this->params['items'])) throw new xException('No items provided', 400);
        // Database action
        $r = xModel::load($this->model, $this->params['items'])->put();
        // Result
        $r['items'] = array_shift(xModel::load($this->model, array('id'=>$r['xinsertid']))->get());
        return $r;
    }

    /**
     * API Method.
     * Generic delete method for API calls.
     * @return array An ExtJS compatible resultset structure.
     */
    function delete() {
        // Checks if method is allowed
        if (!in_array('delete', $this->allow)) throw new xException("Method not allowed", 403);
        // Database action + result
        return xModel::load($this->model, array('id'=>$this->params['id']))->delete();
    }
}