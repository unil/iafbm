<?php

/**
 * Project specific xWebController
 */
class iaWebController extends xWebController {

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
     * @see iaQueryManager::$fields
     */
    var $query_fields = array();

    /**
     * @see iaQueryManager::$transformers
     */
    var $query_transform = array();

    /**
     * Joins to activate when query is active.
     * @see iaQueryManager::$join
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

    function __construct($params=null) {
        parent::__construct($params);
        // Setups auth information,
        // on every call because it can change anytime
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
        // Creates extjs compatible result
        $params = $this->params;
        $count_params = xUtil::filter_keys($params, array('xoffset', 'xlimit', 'xorder_by', 'xorder'), true);
        return array(
            'xcount' => xModel::load($this->model, $count_params)->count(),
            'items' => xModel::load($this->model, $params)->get()
        );
    }

    /**
     * Handles query case (eg. 'xquery' parameter is present in parameters).
     * Transforms existing parameters to induce search behaviour within models.
     */
    protected function handle_query() {
        $qm = xPlugin::load('QueryManager', $this->params);
        $qm->model = $this->model;
        $qm->fields = $this->query_fields;
        $qm->join = $this->query_join;
        $qm->transform = $this->query_transform;
        $p = $qm->run();
        $this->params = array_merge($this->params, $p);
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