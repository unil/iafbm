<?php

/**
 * Specific iaModelMysql implementation for managing journaling models.
 * - Prevents 'post' and 'delete' opertations.
 * - Checks that the accessed row(s) relate to an allowed model.
 * @package iafbm
 */
class iaJournalingModelMysql extends iaModelMysql {

    /**
     * Checks that the requested model_name or table_name is allowed.
     * @see iaModelMysql::check_allowed()
     */
    function check_allowed($operation) {
        $this->check_allowed_model($operation);
        $this->check_allowed_table($operation);
    }

    /**
     * Checks that the given model_name param is/are allowed models,
     * defines model_name to all allowed models if no model_name param is given.
     * @see iaJournalingModelMysql::check_allowed()
     * @param string Operation (get, putm post, delete).
     */
    protected function check_allowed_model($operation) {
        // For 'get' operation on unspecified primary-key nor 'model_name' parameters,
        // applies allowed models filter
        if ($operation == 'get' && !xUtil::filter_keys($this->params, array($this->primary(), 'model_name'))) {
            $allowed_models = array_filter(xModel::scan(), function($model) use ($operation) {
                return xContext::$auth->is_allowed_model($model, $operation);
            });
            $this->params['model_name'] = $allowed_models;
        }
        // Checks that requested models are allowed
        $models = @$this->params['model_name'] ? xUtil::arrize($this->params['model_name']) : array();
        foreach ($models as $model) {
            if (!xContext::$auth->is_allowed_model($model, $operation)) {
                throw new xException ("You are not allowed to '{$operation}' on '{$this->name}' with model '{$model}'", 403);
            }
        }
    }

    /**
     * Checks that the given table_name param relate to allowed models.
     * @see iaJournalingModelMysql::check_allowed()
     * @param string Operation (get, putm post, delete).
     */
    protected function check_allowed_table($operation) {
        // Checks if requested models are allowed
        $tables = @$this->params['table_name'] ? xUtil::arrize($this->params['table_name']) : array();
        foreach (xModel::scan() as $model_name) {
            $model = xModel::load($model_name);
            foreach ($tables as $table) {
                if ($model->maintable == $table && !xContext::$auth->is_allowed_model($model->name, $operation)) {
                    throw new xException ("You are not allowed to '{$operation}' on '{$this->name}' with table '{$table}'", 403);
                }
            }
        }
    }

    function post() {
        throw new xException("{{$this->name} model cannot be modified because it is of type 'journaling'", 403);
    }

    function delete() {
        throw new xException("{{$this->name} model cannot be deleted because it is of type 'journaling'", 403);
    }
}