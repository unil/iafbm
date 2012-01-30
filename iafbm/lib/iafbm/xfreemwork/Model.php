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
     * @param array Specifies which foreign models information will be stored with a version.
     *              The stored information consists of the table-name/id-value of the rows that relates to this record.
     */
    var $version_foreign_models = array();

    /**
     * @param bool True to allow archive.
     */
    var $archivable = false;

    /**
     * @param array Specifies the joins to enable for archive.
     *              The joins must be defined in xModel::$joins.
     *              Ignores joins if array is empty.
     * @see xModel::$joins
     */
    var $archive_join = array();

    /**
     * @param array Specifies the foreign models to include in archive.
     *              Ignores foreign models if array is empty.
     */
    var $archive_foreign_models = array();

    /**
     * TODO:
     * Enhanced invalids method:
     * if xmethod=='post', first load the existing record,
     * then apply modification ($this->params),
     * then validate
     * => this will enable the web service calls that contain only the field-to-be-changed as parameters
     */
    function invalids($fields = array()) {
        return parent::invalids($fields);
    }

    /**
     * Enhanced get method.
     * Manages versioning.
     */
    function get($rownum=null) {
        // FIXME: TODO:
        if (!@$this->params['xversion']) {
            // Unless specified otherwise through 'actif' parameter,
            // adds 'actif'=true in where clause
            // because we do not want to return the soft-deleted records
            if (array_key_exists('actif', $this->mapping) && !isset($this->params['actif'])) {
                $this->params['actif'] = 1;
            }
            return parent::get($rownum);
        } else {
            return $this->get_version($rownum);
        }
    }

    /**
     * Enhanced count method.
     * Counts only active records.
     */
    function count() {
        if (!@$this->params['xversion']) {
            // Unless specified otherwise through 'actif' parameter,
            // adds 'actif'=true in where clause
            // because we do not want to return the soft-deleted records
            if (array_key_exists('actif', $this->mapping) && !isset($this->params['actif'])) {
                $this->params['actif'] = 1;
            }
        }
        return parent::count();
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
                    'xorder_by' => 'id',
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
                    $join_id = $result["{$model}_{$join_primary}"];
                    if (!$join_id) {
                        // If modified $result foreign key is empty,
                        // Creates an empty foreign model that will set foreign fields values to null
                        $join_results = array_fill_keys(array_keys(xModel::load($model)->mapping), null);
                    } else {
                        // Fetches versioned foreign model
                        // Recursive call here (because the xversion parameter is present)
                        $join_results = xModel::load($model, array(
                            'id' => $join_id,
                            'xversion' => $version,
                            'xjoin' => array()
                        ))->get(0);
                    }
                    // Applies foreign model values
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
        // Manages versioning
        $t = new xTransaction();
        $t->start();
        $old_record = xModel::load($this->name, array('id'=>$this->params['id'], 'xjoin'=>array()))->get(0);
        try {
            $result = parent::post();
        } catch (Exception $e) {
            $t->rollback();
            throw $e;
        }
        // In case of soft-deletion, sets $operation to 'delete' instead of 'post'
        $operation = (isset($this->params['actif']) && @$old_record['actif'] && !$this->params['actif']) ? 'delete' : 'post';
        $this->version($operation, $old_record, $result);
        $t->end();
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
        $t = new xTransaction();
        $t->start();
        try {
            $result = parent::put();
        } catch (Exception $e) {
            $t->rollback();
            throw $e;
        }
        $this->version('put', array(), $result);
        $t->end();
        return $result;
    }

    /**
     * Enhanced delete method.
     * Manages versioning.
     */
    function delete() {
        // TODO:
        // Prevent deletion if foreign row exist
        // Ensures primary key(s) parameters are present
        if (!array_intersect(xUtil::arrize($this->primary), array_keys($this->params)))
            throw new xException('Missing primary keys parameter(s) for delete action', 400);
        // Checks for constraints violations
        // by virtually deleting the row within an always ROLLBACK'ed transaction
        $t = new xTransaction();
        $t->start();
        $t->execute($this, '_delete_hard');
        $t->rollback();
        if ($t->exceptions) throw array_shift($t->exceptions);
        // Deletes row softly if all contraints satisfied
        return $this->_delete_soft();
    }
    function _delete_hard() {
        return parent::delete();
    }
    function _delete_soft() {
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
     * @see iaModelMysql::$versioning
     * @see iaModelMysql::$version_fields
     * @see iaModelMysql::$version_foreign_models
     */
    protected function version($operation=null, $old_record=array(), $result=array(), $commentaire=null) {
        // Aborts if versioning is disabled
        if (!$this->versioning) return;
        // Determines changes applied to the record
        $record_id = (strtolower($operation) == 'put') ? $result['insertid'] : $this->params['id'];
        $new_record = xModel::load($this->name, array(
            'id' => $record_id
        ))->get(0);
        $changes = array();
        foreach ($this->mapping as $field) {
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
        // But create a version if the operation is tag
        if (!$changes && $operation != 'tag') return;
        // Writes version
        $id_field_name = implode(',', xUtil::arrize($this->primary));
        $id_field_value = (strtolower($operation) == 'put') ?
            $result['insertid'] :
            $this->params[$id_field_name];
        $version_result = xModel::load('version', array(
            'table_name' => $this->maintable,
            'id_field_name' => $id_field_name,
            'id_field_value' => $id_field_value,
            'model_name' => $this->name,
            'operation' => $operation,
            'commentaire' => $commentaire
        ))->put();
        $version_id = $version_result['insertid'];
        // Do not write version data if no change has been made
        if (!$changes) return $version_result;
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
        return $version_result;
        // Writes foreign tables data
        // FIXME: This is a test implementation, not fully working
        return;
        foreach ($this->version_foreign_models as $foreign_model) {
            $model = xModel::load($foreign_model, array(
                'commission_id' => $id_field_value,
            ));
            $foreign_records = $model->get();
            $foreign_id_field = implode(',', xUtil::arrize($model->primary));
            foreach($foreign_records as $foreign_record) {
                xModel::load('version_relation', array(
                    'version_id' => $version_id,
                    'foreign_table_name' => $model->maintable,
                    'foreign_id_field' => $foreign_id_field,
                    'foreign_id_value' => $foreign_record[$foreign_id_field]
                ))->put();
            }
        }
    }

    /**
     * Creates a tag.
     */
    function tag() {
        $id = @$this->params['id'];
        $commentaire = @$this->params['commentaire'];
        if (!$id) throw new xException('id parameter missing', 403);
        if (!strlen($commentaire)) throw new xException('Commentaire is mandatory', 403);
        $record = xModel::load($this->name, array('id' => $id))->get(0);
        return $this->version('tag', $record, array(), $commentaire);
    }

    /**
     * Creates an archive of a record.
     * Also archives foreign fields specified in {@see iaModelMysql::$archive_join}.
     * Also archives foreign records specified in {@see iaModelMysql::$archive_foreign_models}.
     * @see iaModelMysql::$archivable
     * @see iaModelMysql::$archive_join
     * @see iaModelMysql::$archive_foreign_models
     */
    function archive() {
        if (!$this->archivable)
            throw new xException("Model '{$this->name}' is not archivable", 403);
        if (@$this->params['xversion'])
            throw new xException('Cannot archive an item when xversion parameter is specified', 500);
        // Retrieves model primary key and its specified value
        $primary = $this->primary[0];
        $id = @$this->params[$primary];
        if (!$id) throw new xException("Missing id parameter");
        // Creates the models set to be archived and retrieves data
        $data = array();
        $data[$this->name] = xModel::load($this->name, array(
            $primary => $id,
            'xjoin' => $this->archive_join
        ))->get();
        foreach ($this->archive_foreign_models as $model_name => $foreign_field_name) {
            $data[$model_name] = xModel::load($model_name, array(
                $foreign_field_name => $id,
                'xjoin' => xModel::load($model_name)->archive_join
            ))->get();
        }
        // Creates archive item
        $t = new xTransaction();
        $t->start();
        $archive_result = $t->execute(
            xModel::load('archive', array(
                'table_name' => $this->maintable,
                'id_field_name' => $primary,
                'id_field_value' => $id,
                'model_name' => $this->name
            )),
            'put'
        );
        $archive_id = $t->insertid();
        // Creates archive data for actual model
        foreach ($data as $model_name => $rows) {
            $model_instance = xModel::load($model_name);
            foreach ($rows as $row) {
                foreach ($row as $modelfield => $value) {
                    $row_id_field = $model_instance->primary[0];
                    $row_id_value = $row[$model_instance->primary[0]];
                    if (!$row_id_field || !$row_id_value)
                        throw new xException('Archive failed: undefined row id field or value', 500);
                    $t->execute(
                        xModel::load('archive_data', array(
                            'archive_id' => $archive_id,
                            'model_name' => $model_instance->name,
                            'table_name' => $model_instance->maintable,
                            'table_field_name' => $model_instance->dbfield($modelfield),
                            'model_field_name' => $modelfield,
                            'id_field_name' => $row_id_field,
                            'id_field_value' => $row_id_value,
                            'value' => $value
                        )),
                        'put'
                    );
                }
            }
        }
        $t->end();
        return $archive_result;
    }
}