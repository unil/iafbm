<?php

class VersionsController extends iaExtRestController {
    var $model = 'version';
    var $allow = array('get');

    function defaultAction() {
        return $this->feedAction();
    }

    function feedAction() {
       return xController::load('feed')->defaultAction();
    }

    function historyAction() {
        // Manages given params
        $id = @$this->params['id'];
        $resource = @$this->params['resource'];
        $version = @$this->params['version'];
        // Determines which view to show according the given params
        if ($version) {
            $model = @xController::load($resource)->model;
            $version_info = @array_shift(xModel::load('version', array(
                'model_name' => $model,
                'id_field_value' => $id,
                'id' => $version
            ))->get());
            $diff = xModel::load('version_data', array(
                'version_model_name' => $model,
                'version_id_field_value' => $id,
                'version_id' => $version
            ))->get();
            $record = @array_shift(xModel::load($model, array(
                'id' => $id,
                'xjoin' => @$this->params['xjoin'] ? $this->params['xjoin'] : '',
                'xversion' => $version
            ))->get());
            $all_versions = xModel::load('version', array(
                'model_name' => $model,
                'id_field_value' => $id
            ))->get();
            return xView::load('versions/diff/record', array(
                'resource' => $resource,
                'id' => $id,
                'record' => $record,
                'version' => $version_info,
                'diff' => $diff,
                'versions' => $all_versions
            ));
        }
        if ($id) {
            $model = @xController::load($resource)->model;
            $versions = xModel::load('version', array(
                'model_name' => $model,
                'id_field_value' => $id
            ))->get();
            return xView::load('versions/diff/versions', array(
                'resource' => $resource,
                'id' => $id,
                'versions' => $versions
            ));
        }
        if ($resource) {
            $model = @xController::load($resource)->model;
            $versions = xModel::load('version', array(
                'model_name' => $model,
                'xreturn' => array('count(id) AS count', 'id_field_value', 'table_name'),
                'xgroup_by' => 'id_field_value'
            ))->get();
            return xView::load('versions/diff/resource', array(
                'resource' => $resource,
                'versions' => $versions
            ));
        }
        // Falls back to versioned resources list
        $controllers = xController::scan();
        $controllers = array_filter($controllers, function($controller) {
            $model = xController::load($controller)->model;
            return $model && xModel::load($model)->versioning;
        });
        return xView::load('versions/diff/index', array('controllers' => $controllers));
    }
}