<?php

/**
 * Project specific xWebController
 */
class iaExtRestController extends xWebController {

    var $model = null;

    /**
     * Allowed CRUD operations.
     * Possible values: 'get', 'post', 'put', 'delete'
     * @see get()
     * @see post()
     * @see put()
     * @see delete()
     * @var array
     */
    var $allow = array('get', 'post', 'put', 'delete');

    /**
     * Fields to query on (for model parameters creation on xquery).
     * Eg. when the xquery parameter is provided with a GET method.
     * @see get()
     * @var array
     */
    var $query_fields = array();

    /**
     * Fields to transforme before querying.
     *
     * For each model field name, the array describes
     * - either the search AND replace regular expressions
     * - or the name of one or more transformaers
     *   (as defined in $query_field_transformers).
     *
     * Note that multiple transformers can be specified (see example below).
     *
     * Array structure:
     * <code>
     * array(
     *     'fieldname1' => array('/search-regexp/' => '/replace-regexp/'),
     *     'fieldname2' => 'date-full',
     *     'fieldname3' => 'date-full,date-binomial'
     *     ...
     * )
     * <code>
     * @see get()
     * @see $query_fields
     * @see $query_fields_transformers
     * @var array
     */
    var $query_fields_transform = array();

    var $query_fields_transformers = array(
        'date' => array('/^(\d*)\.(\d*)\.(\d*)$/' => '$3-$2-$1'),
        'date-binomial' => array('/^(\d*)\.(\d*)$/' => '$2-$1')
    );

    /**
     * Joins to activate when query is active.
     * @see xModel::$join
     * @see $query_fields
     * @see get()
     * @var array
     */
    var $query_join = array();

    /**
     * Fields to substitute on sort (for model parameters creation on xsort).
     *
     * Useful for substituing foreign keys (containing numeric ids)
     * with the foreign field name (containing actual text values).
     *
     * For each model field name, the array describes
     * - either the other_fieldname to be used as substitute
     * - or the name of the other_fieldname AND the related join to activate
     *
     * Array structure:
     * <code>
     * array(
     *     'fieldname1' => 'other_fieldname1',
     *     'fieldname2' => array(
     *         'field' => 'other_fieldname2',
     *         'join' => 'other_model'
     *     ),
     *     ...
     * )
     * </code>
     * @see get()
     */
    var $sort_fields_substitutions = array();


    /**
     * Sets auth information
     */
    protected function __construct($params = null) {
        parent::__construct($params);
        // Setting auth information on every call
        // because it can change anytime
        xContext::$auth->set_from_aai();
    }

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
     * Returns controller name
     * @return string Controller name
     */
    protected function get_name() {
        $reflector = new ReflectionClass(get_class($this));
        return substr(basename($reflector->getFileName()), 0, -strlen('.php'));
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
     * @param mixed Any model fields for filtering, or a query parameter for search
     * @return array An ExtJS compatible resultset structure.
     */
    function get() {
        if (!in_array('get', $this->allow)) throw new xException("Method not allowed", 403);
        // Manages query case
        $this->handle_query();
        // Manages sort case
        $this->handle_sort();
        // Fetches data
        $count = xModel::load(
            $this->model,
            xUtil::filter_keys($this->params, array('xoffset', 'xlimit', 'xorder_by', 'xorder'), true)
        )->count();
        $items = xModel::load(
            $this->model,
            $this->params
        )->get();
        // Determines wheter to return a 404 status
        $pk = xModel::load($this->model)->primary();
        $fields = array_keys(xModel::load($this->model)->mapping);
        $by_id = in_array($pk, array_keys($this->params)) && !array_intersect($this->params, $fields);
        if ($by_id && !$count) {
            $resource = xModel::load($this->model)->name;
            throw new xException("The requested {$resource} ({$pk}:{$this->params[$pk]}) does not exist", 404);
        }
        // Creates extjs compatible result
        return array(
            'xcount' => $count,
            'items' => $items
        );
    }

    /**
     * Handles query case (eg. 'xquery' parameter is present in parameters).
     * Transforms existing parameters to induce search behaviour within models.
     */
    protected function handle_query() {
        $params = $this->params;
        if (strlen(@$params['xquery']) > 0) {
            $query = $params['xquery'];
            // Activates join(s) specified in $query_join,
            // preserving already active joins
            $params['xjoin'] = array_merge(
                array_keys(xModel::load($this->model, $params)->joins()),
                array_keys(xModel::load($this->model, array('xjoin' => $this->query_join))->joins())
            );
            // Retrieve model fields names list (including foreign fields)
            $model = xModel::load($this->model, $params);
            $fields = array_merge(
                array_keys($model->mapping),
                array_keys($model->foreign_mapping())
            );
            // Adds (specified if applicable) model fields
            foreach ($fields as $field) {
                // Skips model field if $this->query_field exists but $field not in list
                if ($this->query_fields && !in_array($field, $this->query_fields)) continue;
                // Skips fields existing in params:
                // these are to be used as constraint
                if (in_array($field, array_keys($this->params), true)) continue;
                // Transforms parameter data if applicable
                // @see $query_fields_transform
                if (in_array($field, array_keys($this->query_fields_transform))) {
                    // Retrieves transformers information
                    $transform = $this->query_fields_transform[$field];
                    $infos = array();
                    if (is_array($transform)) {
                        // TODO
                        $infos[] = array(
                            'search' => array_shift(array_keys($transform)),
                            'replace' => array_shift(array_values($transform))
                        );
                    } else {
                        $transformers = array_map('trim', explode(',', $transform));
                        foreach ($transformers as $transformer) {
                            $transformer = $this->query_fields_transformers[$transformer];
                            $infos[] = array(
                                'search' => array_shift(array_keys($transformer)),
                                'replace' => array_shift(array_values($transformer))
                            );
                        }
                    }
                    // Applies transformers to query value
                    foreach ($infos as $info) {
                        $query_transformed = preg_replace($info['search'], $info['replace'], $query, -1, $count);
                        if ($query_transformed === null) throw new xException("Error transforming query parameter for field ({$field}), value ({$query})", 500);
                        // If transformer worked, stop here
                        // Otherwise, continue with next transformer
                        if ($count) {
                            $query = $query_transformed;
                            break;
                        }
                    }
                }
                // Adds model parameters
                $params[$field] = "%{$query}%";
                $params["{$field}_comparator"] = 'LIKE';
                $params["{$field}_operator"] = 'OR';
            }
            // Uses 'query' where-template
            // if it is supported by the actual xModel and no 'xwhere' param is defined
            if (@xModel::load($this->model)->wheres['query'] && !$this->params['xwhere']) {
                $params['xwhere'] = 'query';
            }
            // Removes query param
            unset($params['xquery']);
            // Sets modified parameters
            $this->params = $params;
        }
    }

    /**
     * Handles sort case (eg. 'xsort' parameter is present in parameters).
     * Transforms existing parameters to change sort parameters within models.
     */
    protected function handle_sort() {
        $params = $this->params;
        if (strlen(@$params['xsort']) > 0) {
            $info = array_shift(json_decode($params['xsort']));
            $property = @$info->property;
            $direction = @$info->direction;
            // Manages substitutions
            // @see self::$sort_fields_substitutions
            if (in_array($property, array_keys($this->sort_fields_substitutions))) {
                // Substitutes field name
                $info = $this->sort_fields_substitutions[$property];
                if (is_array($info)) {
                    $property_substitued = @$info['field'];
                    $join = @$info['join'];
                } else {
                    $property_substitued = $info;
                    $join = null;
                }
                if (!$property_substitued) throw new xException("Error substituing field ($property_substitued)");
                $property = $property_substitued;
                // Activates join(s) relative to field (if applicable),
                // preserving already active joins
                if ($join) {
                    $params['xjoin'] = array_merge(
                        array_keys(xModel::load($this->model, $params)->joins()),
                        array_keys(xModel::load($this->model, array('xjoin' => $join))->joins())
                    );
                }
            }
            // Adds model parameters
            $params['xorder_by'] = $property;
            $params['xorder'] = $direction;
            unset($params['xsort']);
            // Sets modified parameters
            $this->params = $params;
        }
    }

    /**
     * API Method.
     * Generic post method for API calls.
     * @param array items: contains an array of model fields and values.
     * @return array An ExtJS compatible resultset structure.
     */
    function post() {
        // Checks if method is allowed
        if (!in_array('post', $this->allow)) throw new xException("Method not allowed", 403);
        // Checks provided parameters
        if (!isset($this->params['items'])) throw new xException('No items provided', 400);
        // Prevents posting a versioned record
        if (@$this->params['xversion']) throw new xException('Cannot post a versioned record', 400);
        // Checks for params.id and params.items.id consistency
        // (this test is only for precaution: params.id is not used in anyway)
        if (@$this->params['id'] != @$this->params['items']['id'])
            throw new xException("Parameters id and items.id do not match", 400);
        // Database action
        $r = xModel::load($this->model, $this->params['items'])->post();
        // Result
        $i = xController::load($this->get_name(), array('id'=>$this->params['items']['id']))->get();
        $r['items'] = array_shift($i['items']);
        return $r;
    }

    /**
     * API Method.
     * Generic put method for API calls.
     * @param array items: contains an array of model fields and values.
     * @return array An ExtJS compatible resultset structure.
     */
    function put() {
        // Checks if method is allowed
        if (!in_array('put', $this->allow)) throw new xException("Method not allowed", 403);
        // Checks provided parameters
        if (!isset($this->params['items'])) throw new xException('No items provided', 400);
        // Prevents posting a versioned record
        if (@$this->params['xversion']) throw new xException('Cannot put a versioned record', 400);
        // Checks for params.id and params.items.id consistency
        // (this test is only for precaution: params.id is not used in anyway)
        if (@$this->params['id'] != @$this->params['items']['id'])
            throw new xException("Parameters id and items.id do not match", 400);
        // Database action
        $r = xModel::load($this->model, $this->params['items'])->put();
        // Result
        $i = xController::load($this->get_name(), array('id'=>$r['xinsertid']))->get();
        $r['items'] = array_shift($i['items']);
        return $r;
    }

    /**
     * API Method.
     * Generic delete method for API calls.
     * @note This method is to be used as default. For nn relationship tables,
     *       one should refine the method in specific controller classes.
     * @param integer id: the id parameter of the record to delete
     * @return array An ExtJS compatible resultset structure.
     */
    function delete() {
        // Checks if method is allowed
        if (!in_array('delete', $this->allow)) throw new xException("Method not allowed", 403);
        // Database action + result
        return xModel::load($this->model, array('id'=>@$this->params['id']))->delete();
    }

    /**
     * API Method.
     * Creates a tag.
     * A tag consists in a version generated by the user.
     * @param integer id: the id parameter of the record to tag (version)
     * @return array An ExtJS compatible resultset structure.
     */
    function tag() {
        return xModel::load($this->model, array(
            'id' => @$this->params['id'],
            'commentaire' => @$this->params['commentaire']
        ))->tag();
    }

    /**
     * API Method.
     * Returns history for a given record.
     * @param int id: id of the record
     * @return array An array containing the record history
     */
    function history() {
        $id = @$this->params['id'];
        if (!$id) throw new xException('Missing id parameter');
        // Retrieves all versions that impact this record
        // (directly or indirectly)
        $r = xModel::load('version_relation', array(
            'model_name' => $this->model,
            'id_field_value' => $id,
            'xorder_by' => 'versions_relations.version_id',
            'xorder' => 'DESC'
        ))->get();
        $versions = array();
        foreach ($r as $rr) $versions[] = $rr['version_id'];
        //
        $data = array();
        foreach ($versions as $version) {
            $data[$version] = array(
                'version' => xModel::load('version', array(
                    'id' => $version
                ))->get(0),
                'modifications' => xModel::load('version_data', array(
                    'version_id' => $version,
                    'xjoin' => array()
                ))->get(),
            );
        }
        return $data;
    }
}