<?php

/**
 * Specific iaModelMysql implementation for managing journaling models.
 * - Prevents 'post' and 'delete' opertations.
 * - Checks that the accessed row(s) relate to an allowed model.
 * @package iafbm
 */
abstract class iaJournalingModelMysql extends iaModelMysql {

    /**
     * Checks that the requested model_name or table_name is allowed.
     * @see iaModelMysql::check_allowed()
     */
    function check_allowed($operation) {
        $this->check_allowed_model($operation);
    }

    /**
     * Checks that the given model_name param is/are allowed models,
     * defines model_name to all allowed models if no model_name param is given.
     * @see iaJournalingModelMysql::check_allowed()
     * @param string Operation (get, putm post, delete).
     */
    protected function check_allowed_model($operation) {
        $model_field_name = $this->model_field_name();
        // Prevents 'get' operation with unspecified 'model_name' parameters,
        if ($operation == 'get' && !xUtil::filter_keys($this->params, $model_field_name)) {
            throw new xException (
                "Please specify '{$model_field_name}' parameter",
                400,
                array(
                    'params' => $this->params,
                    'model' => $this->name,
                    'join' => $this->join,
                    'mapping' => array_merge(
                        array_keys($this->mapping),
                        array_keys($this->foreign_mapping())
                    )
                )
            );
        }
        // Prevents requesting an unallowed model
        $models = @$this->params[$model_field_name] ? xUtil::arrize($this->params[$model_field_name]) : array();
        foreach ($models as $model) {
            if (!xContext::$auth->is_allowed_model($model, $operation)) {
                throw new xException ("You are not allowed to '{$operation}' on '{$this->name}' with model '{$model}'", 403);
            }
        }
    }

    /**
     * Determines and return the field name related to 'model_field'
     * (can be a local for a foreign field)
     * @return string The name of the field related to 'model_field'
     */
    function model_field_name() {
        // Feteches all modelfields names
        $mapping = array_merge(
            array_keys($this->mapping),
            array_keys($this->foreign_mapping())
        );
        // Determines which one relates to modelfield,
        // priority is given to local field name because
        // it is merged first (see argument order in array_merge() above)
        foreach ($mapping as $field) {
            if (substr($field, -strlen('model_name')) == 'model_name') {
                return $field;
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