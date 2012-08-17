<?php

/**
 * Project specific xModelMysql.
 * - Manages model-level permissions
 * - Manages versioning
 * - Manages archive
 * - Manages soft-delete
 * @package iafbm
 */
abstract class iaModelMysql extends xModelMysql {

    /**
     * True to enable versioning on this model.
     * @var bool
     */
    var $versioning = true;

    /**
     * Specifies which fields have to be versioned.
     * Versions all fields if array is empty.
     * @var array
     */
    var $version_fields = array();

    /**
     * True to allow archive.
     * @var bool
     */
    var $archivable = false;

    /**
     * Specifies the foreign models to include in archive.
     * Ignores foreign models if array is empty.
     * The array form is:
     * <code>
     * array(
     *     'foreign_model_name' => 'foreign_model_field_value_to_match_with_this_model_primary_key'
     *     'foreign_model_name' => 'this_model_foreign_field_name'
     * )
     * </code>
     * or
     * <code>
     * array(
     *     'foreign_model_name' => array(
     *         'local_model_field_name_to_match_value_with' => 'foreign_model_field_name'
     *     )
     * )
     * </code>
     * @var array
     * FIXME: This property should be renamed
     *        to a more generic name such as 'foreign_models'
     *        for it is used in both versioning and archiving mechanisme
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
     * Checks that the model is allowed for the given $operation.
     * @param string Operation (get, putm post, delete).
     * @param string Model name (optional, defaults to current model).
     */
    function check_allowed($operation, $modelname=null) {
        if (!$modelname) $modelname = $this->name;
        if (!xContext::$auth->is_allowed_model($modelname, $operation)) {
            $roles = implode(', ', xContext::$auth->roles());
            throw new xException ("You are not allowed to '{$operation}' on '{$modelname}' with roles '{$roles}'", 403);
        }
    }

    /**
     * Enhances parent method with permissions check.
     * Prevents joining with unallowed models.
     * @see xModelMysql::sql_join()
     * @return string
     */
    function sql_join() {
        // Iteractes activated joins models to check if allowed
        foreach ($this->joins() as $model => $join) {
            $this->check_allowed('get', $model);
        }
        return "\t".implode($this->joins(), "\n\t");
    }

    /**
     * Enhanced get method.
     * Manages versioning.
     */
    function get($rownum=null) {
        $this->check_allowed('get');
        // Returns versioned record if 'xversion' parameter is specified
        if (@$this->params['xversion']) return $this->get_version($rownum);
        // Manages default value for 'actif' parameter
        if (array_key_exists('actif', $this->mapping) && !isset($this->params['actif'])) {
            // defaults to 'actif'=true in where clause
            // because we do not want to return the soft-deleted records
            $this->params['actif'] = 1;
        } elseif (@sort($this->params['actif']) == array(0,1)) {
            // if both actif and soft-deleted records
            // are to be retrieved, 'actif' parameter can simply be removed
            unset($this->params['actif']);
        }
        return parent::get($rownum);
    }

    /**
     * Enhanced count method.
     * Counts only active records.
     */
    function count() {
        $this->check_allowed('get');
        if (!@$this->params['xversion']) {
            // Unless specified otherwise through 'actif' parameter,
            // adds 'actif'=true in where clause
            // because we do not want to count the soft-deleted records
            if (array_key_exists('actif', $this->mapping) && !isset($this->params['actif'])) {
                $this->params['actif'] = 1;
            }
        }
        return parent::count();
    }

    protected function get_version($rownum=null) {
        $primary = $this->primary();
        $version = @$this->params['xversion'];
        // Manages params for correct versions increments application
        $params_pristine = $this->params;
        unset($this->params['actif']); // Also retrive 'deleted' rows
        $results = parent::get();
        // Checks if version exists
        // NOTE: Custom SQL query to bypass iaJournalingModel::check_allowed()
        //       that prevents unspecified 'model_name' parameter,
        //       because of recursivity for foreign fields,
        //       model_name is not always $this->name (?)
        $version_count = mysql_num_rows(
            xModel::q("SELECT * FROM versions WHERE id = '{$version}';")
        );
        if (!$version_count) {
            throw new xException("Version {$version} does not exist", 404);
        }
        // Creates versionned results
        foreach ($results as $position => &$result) {
            $record_id = @$result[$primary];
            // Ensures the record id is available
            if (!$record_id) throw new xException(
                "Record primary key ('{$primary}') field must be specified ".
                "in 'xreturn' parameter if 'xversion' parameter is specified"
            );
            $modifications = xModel::load('version_data', array(
                'version_model_name' => $this->name,
                'version_id_field_value' => $record_id,
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
                $join_id = @$result["{$model}_{$join_primary}"];
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
        // Reverts modified params
        $this->params = $params_pristine;
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
        $this->check_allowed('post');
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
            // In case of soft-deletion,
            // sets $operation to 'delete' instead of 'post'
            $operation = (isset($this->params['actif']) && @$old_record['actif'] && !$this->params['actif']) ? 'delete' : 'post';
            $version = $this->version($operation, $old_record, $result);
        } catch (Exception $e) {
            $t->rollback();
            throw $e;
        }
        $t->end();
        // Returns result with current version information
        $result['xversion'] = $version;
        return $result;
    }

    /**
     * Enhanced put method.
     * Manages versioning.
     */
    function put() {
        $this->check_allowed('put');
        // Sets user id as creator
        if (isset($this->mapping['creator'])) {
            $this->params['creator'] = xContext::$auth->username();
        }
        // Bypasses versioning if not applicable
        if (!$this->versioning) return parent::put();
        // Manages versioning
        $t = new xTransaction();
        $t->start();
        try {
            $result = parent::put();
            $version = $this->version('put', array(), $result);
        } catch (Exception $e) {
            $t->rollback();
            throw $e;
        }
        $t->end();
        // Returns result with current version information
        $result['xversion'] = $version;
        return $result;
    }

    /**
     * Enhanced delete method.
     * Manages versioning.
     */
    function delete() {
        $this->check_allowed('delete');
        // Ensures primary key(s) parameters are present
        if (!array_intersect(xUtil::arrize($this->primary), array_keys($this->params)))
            throw new xException('Missing primary keys parameter(s) for delete action', 400);
        // Hard/Soft delete switch:
        // a record is hard deletable if it has no 'actif' field
        if (array_key_exists('actif', $this->mapping)) {
            // Soft delete:
            // Checks for constraints violations
            // by virtually deleting the row within an always ROLLBACK'ed transaction.
            // Note: This transaction uses a separate db connection
            // in order to unconditionally rollback the delete
            // without interfering with any pending transaction.
            if ($this->_is_deletable()) return $this->_delete_soft();
        } else {
            // Hard delete
            return parent::delete();
        }
    }

    /**
     * Performs a unconditionally ROLLBACKed DELETE statements to test contraints.
     * @return boolean True if the row deletion passes contraints checks.
     */
    function _is_deletable() {
        // Delete hard uses a separate db connection
        // in order to unconditionally rollback the delete
        // without interfering with any pending transaction.
        $db = xContext::$bootstrap->create_db();
        // BEGIN a separate transaction
        xModel::q('SET AUTOCOMMIT=0');
        // Executes query
        $id = $this->params[$this->primary()];
        $sql = "DELETE FROM {$this->maintable} WHERE id = {$id}";
        $r = xModel::q($sql);
        // ROLLBACK the separate transaction
        xModel::q('ROLLBACK');
        //
        return (bool)$r;
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
     * Creates a version of a record, returning the created version id.
     * Compares fields from old and new records provided
     * and saves the modified fields as a version.
     * @param string Name of the performed operation (get, put, post, delete)
     * @param array Old version of the record.
     * @param array New version of the record.
     * @return int The created version id, or null if no version was created.
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
            'id' => $record_id,
            'actif' => ($operation=='delete') ? 0 : 1
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
        if(!$version_id) throw new xException('Error while creating version');
        // Writes version data (if any)
        foreach ($changes as $field => $value) {
            xModel::load('version_data', array(
                'version_id' => $version_id,
                'field_name' => $field,
                'old_value' => $value['old'],
                'new_value' => $value['new']
            ))->put();
        }
        // Writes impacted records names and ids
        // (stores id as param for self::version_get_impacted_records())
        $this->params[$this->primary()] = $record_id;
        $this->version_store_relations($version_result['xinsertid']);
        // Returns version result
        return $version_result;
    }

    protected function version_get_relations($relations=array()) {
        // Parses all models in and keeps the ones with a
        // $archive_foreign_models that relates to this one
        // (following relations backwards)
        $files = scandir(xContext::$basepath.'/models');
        $files = array_diff($files, array('.', '..'));
        foreach ($files as $file) {
            if (!preg_match('/\.php$/', $file)) continue;
            $modelname = substr($file, 0, -strlen('.php'));
            $model = xModel::load($modelname);
            $relation = xUtil::filter_keys(
                $model->archive_foreign_models,
                $this->name
            );
            if (!$relation) continue;
            $relations = xUtil::array_merge(
                array($modelname => $relation),
                $model->version_get_relations($relations)
            );
        }
        return $relations;
    }
    protected function version_get_impacted_records() {
        $id = @$this->params[$this->primary()];
        if (!$id) throw new xException("Missing id parameter");
        $relations = $this->version_get_relations();
        // Adding root model record id to initial $data
        $data = array();
        $data[$this->name] = array($id);
        // Queried models list (see anchor #1)
        $queried = array();
        $queried[$this->name] = true;
        // Loops until all relations are processed, including postponed relations (see anchor #1)
        while (count($relations)) {
            // Foreach (potential) relation found, retrieves all the related records
            foreach ($relations as $modelname => $vias) {
                foreach ($vias as $via_modelname => $foreign_field_info) {
                    // Determines foreign fields names and values
                    // according the $this->archive_foreign_models definition flavour
                    // FIXME: this code duplicates with self::archive_data(), please refactor.
                    if (is_array($foreign_field_info)) {
                        $local_field_name = array_shift(array_keys($foreign_field_info));
                        $foreign_field_name = array_shift(array_values($foreign_field_info));
                    } else {
                        // The given foreign field equals the local primary key (id) value
                        $local_field_name = xModel::load($via_modelname)->primary();
                        $foreign_field_name = $foreign_field_info;
                    }
                    if (!$local_field_name || !$foreign_field_name) {
                        throw new xException(
                            "Could not determine local and/or foreign field name for archive ({$local_field_name}/{$foreign_field_name})",
                            500,
                            array(
                                'model' => $modelname,
                                'via' => $via_modelname,
                                'foreign field info' => $foreign_field_info
                            )
                        );
                    }
                    // Skips and postpone this $via_modelname if not yet queried (anchor #1)
                    if (!@$queried[$via_modelname]) continue;
                    // Fetches impacted records ids (through $via_modelname)
                    $foreign_params = array(
                        xModel::load($via_modelname)->primary() => @$data[$via_modelname],
                        'xjoin' => array()
                    );
                    if ($this->name == $via_modelname) {
                        // (In case of a 'delete' operation, the actual deleted record
                        // must be fetched too. Therefore we also retrieve deleted records
                        // in this case)
                        $foreign_params['actif'] = array(0,1);
                    }
                    $foreign_records = xModel::load($via_modelname, $foreign_params)->get();
                    $foreign_ids = array();
                    foreach ($foreign_records as $foreign_record) {
                        $foreign_ids[] = $foreign_record[$foreign_field_name];
                    }
                    $foreign_ids = array_unique($foreign_ids, SORT_NUMERIC);
                    // Fetches impacted records
                    $local_records = xModel::load($modelname, array(
                        $local_field_name => $foreign_ids,
                        'xjoin' => array()
                    ))->get();
                    // Stores impacted records names and ids, if any
                    foreach ($local_records as $record) {
                        $data[$modelname][] = $record[xModel::load($modelname)->primary()];
                    }
                    // Deletes processed relation (keeping only postponed relations, see anchor #1)
                    unset($relations[$modelname][$via_modelname]);
                    if (!count($relations[$modelname])) unset($relations[$modelname]);
                    // Declare model as queried (see anchor #1)
                    $queried[$modelname] = true;
                }
            }
        }
        // Removes duplicate ids per model
        foreach ($data as $model => &$ids) $ids = array_unique($ids, SORT_NUMERIC);
        // Returns data structure
        return $data;
    }
    protected function version_store_relations($version_id=null) {
        $t = new xTransaction();
        $t->start();
        // Stores impacted records (models names and ids)
        $impacted_records = $this->version_get_impacted_records();
        foreach ($impacted_records as $modelname => $ids) {
            $model = xModel::load($modelname);
            foreach ($ids as $id) {
                // Inserts version_relation records
                xModel::load('version_relation', array(
                    'version_id' => $version_id,
                    'table_name' => $model->maintable,
                    'model_name' => $model->name,
                    'id_field_name' => $model->primary(),
                    'id_field_value' => $id
                ))->put();
            }
        }
        return $t->end();
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
        if (!$record) throw new xException("Record of type '{$this->name}' with id '{$id}' was not found", 404);
        // Prevents creating two consecutive tags with identical system state
        // (ie. with no version impacting that record in between)
        $last_tag = xModel::load('version', array(
            'model_name' => $this->name,
            'id_field_value' => $id,
            'operation' => 'tag',
            'xorder_by' => 'id',
            'xorder' => 'DESC'
        ))->get(0);
        $last_tag_version_id = @$last_tag['id'];
        $versions_since_last_tag = xModel::load('version_relation', array(
            'model_name' => $this->name,
            'id_field_value' => $id,
            'version_id' => $last_tag_version_id,
            'version_id_comparator' => '>'
        ))->get();
        if ($last_tag_version_id && !count($versions_since_last_tag)) {
            throw new xException("Cannot create tag: no modifications since last tag (version id: {$last_tag_version_id})");
        }
        // Creates actual tag
        return $this->version('tag', $record, array(), $commentaire);
    }

    /**
     * Returns an array of data for the model (including its specified archive_foreign_models)
     * Returned array form:
     * <code>
     * array(
     *     'model_name_1' => array(
     *         array(row_1),
     *         array(row_2),
     *         ...
     *     ),
     *     'model_name_2' => array(
     *         array(row_1),
     *         array(row_2),
     *         ...
     *     ),
     *     ...
     * )
     * </code>
     * @param array An array of data (for recursion)
     * @return array An array of data to archive.
     */
    protected function archive_data($data=array()) {
        // Fetches model data
        $items = xModel::load($this->name, array_merge(
            $this->params,
            array('xjoin' => array())
        ))->get();
        // Fetches foreign models data (recursion)
        foreach ($this->archive_foreign_models as $model_name => $foreign_field_info) {
            // Determines foreign fields names and values
            // according the $this->archive_foreign_models definition flavour
            if (is_array($foreign_field_info)) {
                $local_field_name = array_shift(array_keys($foreign_field_info));
                $foreign_field_name = array_shift(array_values($foreign_field_info));
            } else {
                // The given foreign field equals the local primary key (id) value
                $local_field_name = $this->primary();
                $foreign_field_name = $foreign_field_info;
            }
            if (!$local_field_name || !$foreign_field_name)
                throw new xException('Could not determine local and/or foreign field name for archive');
            // Fetches foreign model items for each local item
            foreach($items as $item) {
                // Ensures that local field name exists in item data
                if (!in_array($local_field_name, array_keys($item)))
                    throw new xException("Local field ({$this->name}.{$local_field_name}) does not exist", 500, $item);
                $foreign_model = xModel::load($model_name, array(
                    $foreign_field_name => $item[$local_field_name],
                    'xjoin' => array()
                ));
                // Adds foreign model data to actual data (for recursion)
                // if item not already existing in actual data
                $duplicate=false;
                foreach (@$data[$foreign_model->name] ? $data[$foreign_model->name] : array() as $d) {
                    if ($d[$foreign_field_name] == $item[$local_field_name]) {
                        $duplicate=true;
                        continue;
                    }
                }
                if (!$duplicate) $data = $foreign_model->archive_data($data);
            }
        }
        // Adds local model data to actual data
        if ($items) $data[$this->name] = xUtil::array_merge(@$data[$this->name], $items);
        return $data;
    }

    /**
     * Creates an archive of a record.
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
        $primary = $this->primary();
        $id = @$this->params[$primary];
        if (!$id) throw new xException("Missing id parameter");
        // Creates the models set to be archived and retrieves data
        $data = $this->archive_data();
        $data = array_reverse($data);
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
                    $row_id_field = $model_instance->primary();
                    $row_id_value = $row[$model_instance->primary()];
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