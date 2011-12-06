<?php

/**
 * Project specific xModelMysql
 */
class iaModelMysql extends xModelMysql {

    /**
     * @param bool True to enable versioning on this model.
     */
    var $versioning = true;

    /**
     * @param array Specifies which fields have to be versioned.
     *              Versions all fields if array is empty.
     */
    var $version_fields = array();

    /**
     * Enhanced get method.
     * Manages versioning.
     */
    function get($rownum=null) {
        // FIXME: TODO:
        // Add 'actif'=true in where clause (only if actif field exists in fields mapping array)
        if (!@$this->params['xversion']) {
            if (!isset($this->params['actif'])) $this->params['actif'] = 1;
            return parent::get($rownum);
        } else {
            return $this->get_version($rownum);
        }
    }

    protected function get_version($rownum=null) {
        unset($this->params['actif']); // Also retrive 'deleted' rows
        $results = parent::get();
        $primary = array_shift(xUtil::arrize($this->primary));
        $version = @$this->params['xversion'];
        // Checks if version exists
        if (!xModel::load('version', array('id'=>$version))->get()) {
            throw new xException("Version {$version} does not exist", 404);
        }
        // Creates versionned results
        foreach ($results as $position => &$result) {
            if ($version) {
                $modifications = xModel::load('version_data', array(
                    'version_table_name' => $this->maintable,
                    'version_id_field_value' => $result[$primary],
                    'version_id' => $version,
                    'version_id_comparator' => '>',
                    'xorder_by' => 'version_created',
                    'xorder' => 'DESC'
                ))->get();
                // Applies versions modifications
                foreach ($modifications as $modification) {
                    $modelfield = $this->modelfield($modification['field_name']);
                    $result[$modelfield] = $modification['old_value'];
                }
                // Applies joined models modifications
                foreach ($this->joins() as $model => $sql) {
                    $join_primary = array_shift(xUtil::arrize(xModel::load($model)->primary));
                    // Recursive call here (because the xversion parameter is present)
                    $join_results = xModel::load($model, array(
                        'id' => $result["{$model}_{$join_primary}"],
                        'xversion' => $version,
                        'xjoin' => array()
                    ))->get(0);
                    foreach ($join_results as $modelfield => $value) {
                        $result["{$model}_{$modelfield}"] = $value;
                    }
                }
                // Removes row if it was
                // - not yet existing at the given revision (id=null)
                // - deleted at the given revision (actif=0)
                // FIXME: This results in a wrong 'count' property in JSON result
                //        issued by the controller
                if (!$result[$primary] || (isset($result['actif']) && !$result['actif'])) {
                    unset($results[$position]);
                }
            }
        }
        // Reindexes results array:
        // previous unset() may have resulted in non-contiguous array indices)
        $results = array_values($results);
        // Manages $rownum
        if (!is_null($rownum)) return @$results[$rownum] ? $results[$rownum] : array();
        else return $results;
    }

    /**
     * Enhanced post method.
     * Manages versioning.
     */
    function post() {
        // Ensures primary key(s) parameters are present
        if (!array_intersect(xUtil::arrize($this->primary), array_keys($this->params)))
            throw new xException('Missing primary keys parameter(s) for post action', 400);
        // Bypasses versioning if not applicable
        if (!$this->versioning) return parent::post();
        // Manages versioning:
        $old_record = xModel::load($this->name, array('id'=>$this->params['id'], 'xjoin'=>array()))->get(0);
        $result = parent::post();
        $this->version('post', $old_record, $result);
        return $result;
    }

    /**
     * Enhanced put method.
     * Manages versioning.
     */
    function put() {
        // Bypasses versioning if not applicable
        if (!$this->versioning) return parent::put();
        // Manages versioning
        $result = parent::put();
        $this->version('put', array(), $result);
        return $result;
    }

    /**
     * Enhanced delete method.
     * Manages versioning.
     */
    function delete() {
        // Ensures primary key(s) parameters are present
        if (!array_intersect(xUtil::arrize($this->primary), array_keys($this->params)))
            throw new xException('Missing primary keys parameter(s) for delete action', 400);
        // Sets record as deleted (actif=0)
        $this->params['actif'] = '0';
        return $this->post();
    }

    /**
     * Returns true if the specified field is versionnable.
     * @param string Field name to be tested.
     * @return boolean True if the given field is versionable, false otherwise.
     */
    protected function is_versionable($field) {
        return $this->versioning && !$this->version_fields || xUtil::filter_keys($this->version_fields, $field);
    }

    /**
     * Creates a version of a record.
     * Compares fields from old and new records provided
     * and saves the modified fields as a version.
     * @param string Name of the performed operation (get, put, post, delete)
     * @param array Old version of the record.
     * @param array New version of the record.
     */
    protected function version($operation=null, $old_record=array(), $result=array()) {
        // Aborts if versioning is disabled
        if (!$this->versioning) return;
        // Determines changes applied to the record
        $record_id = (strtolower($operation) == 'put') ? $result['insertid'] : $this->params['id'];
        $new_record = xModel::load($this->name, array(
            'id' => $record_id,
            'actif' => array(0,1)
        ))->get(0);
        $changes = array();
        foreach ($this->fields_values() as $dbfield => $value) {
            $field = $this->modelfield($dbfield);
            // Prevents versioning undesired fields
            if (!$this->is_versionable($field)) continue;
            // Compares record field values, keeping only modified fields
            if (@$old_record[$field] != @$new_record[$field]) {
                $changes[$field] = array(
                    'old' => @$old_record[$field],
                    'new' => @$new_record[$field]
                );
            }
        }
        // Do not create a version if the record was not modified
        if (!$changes) return;
        // Writes version
        $id_field_name = implode(',', xUtil::arrize($this->primary));
        $id_field_value = (strtolower($operation) == 'post') ?
            $this->params[$id_field_name] :
            $result['insertid'];
        $version_result = xModel::load('version', array(
            'table_name' => $this->maintable,
            'id_field_name' => $id_field_name,
            'id_field_value' => $id_field_value,
            'model_name' => $this->name,
            'operation' => $operation
        ))->put();
        $version_id = $version_result['insertid'];
        if(!$version_id) throw new xException('Error while creating version');
        // Writes version data
        foreach ($changes as $field => $value) {
            xModel::load('version_data', array(
                'version_id' => $version_id,
                'field_name' => $field,
                'old_value' => $value['old'],
                'new_value' => $value['new']
            ))->put();
        }
    }
}