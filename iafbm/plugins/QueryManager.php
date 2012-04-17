<?php

class QueryManagerPlugin extends xPlugin {

    /**
     * Model name to be queried.
     * @var string The name of the model to query
     */
    public $model = null;

    /**
     * Default fields to query on.
     * Eg. when the xquery parameter is provided with a GET method.
     * @var array An array containing model fields names
     */
    public $fields = array();

    /**
     * Joins to activate for the query
     * @see xModel::$join
     * @var array An array containing model names
     */
    public $join = array();

    /**
     * Fields transformers: transform field(s) value(s).
     *
     * For each model field name, the array describes
     * - either the search AND replace regular expressions
     * - or the name of one or more transformers
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
     * @var array An array containing fields and transformer(s) name(s)
     */
    public $transform = array();

    /**
     * Available Transformers.
     * @var array
     */
    protected $transformers = array(
        'date' => array('/^(\d*)\.(\d*)\.(\d*)$/' => '$3-$2-$1'),
        'date-binomial' => array('/^(\d*)\.(\d*)$/' => '$2-$1')
    );

    /**
     * Operators mapping.
     *
     * Query syntax operators to xModel operators mapping.
     * @var array
     */
    protected $operators = array(
        ' ' => 'AND',
        ',' => 'OR'
    );

    /**
     * Returns an array of xModel-compatible parameters
     * that reflects the 'fulltext search string' ('xquery' parameter).
     * @return array
     */
    public function params() {
        $q = @$this->params['xquery'];
        // Skips if no query issued
        if (strlen($q) < 1) return array();
        // Makes structure from xquery parameter
        return $this->make_params();
    }

    protected function make_params() {
        $query = $this->get_query();
        $p = array();
        // Sets join parameter, keeping already active joins
        $p['xjoin'] = array_unique(array_merge(
            array_keys(xModel::load($this->model, $this->params)->joins()),
            array_keys(xModel::load($this->model, array('xjoin' => $this->join))->joins())
        ));
        // Adds fields values
        foreach ($query as $part) {
            $p = array_merge($p, $this->make_param($part, $p));
        }
        // Sets query template (xwhere)
        $p['xwhere'] = 'query-fulltext';
        // Returns generated params
        return $p;
    }
    protected function make_param($query_part, $existing_params=array()) {
        $p = array();
        $fields = $query_part['field'] ?
            xUtil::arrize($query_part['field']) :
            $this->get_fields();
        // Creates fields values
        // (values are always set as an array)
        foreach($fields as $field) {
            $value = $this->transform($field, $query_part['value']);
            $values = @$existing_params[$field] ? $existing_params[$field] : array();
            array_push($values, $value);
            $p[$field] = $values;
            //$p["{$field}_operator"] = $query_part['operator'];
        }
        return $p;
    }

    /**
     * Transforms a field value according its transform definition.
     * @see $transform
     * @see $transformers
     * @param string Model field name.
     * @param string Value to transform.
     * @return string Transformed value.
     */
    protected function transform($field, $value) {
        $transform = @$this->transform[$field];
        if (!$transform) return $value;
        // Retrieves transformers information,
        // can be an array (plain search/replace regexp) or transformer(s) name(s)
        $infos = array();
        if (is_array($transform)) {
            $infos[] = array(
                'search' => array_shift(array_keys($transform)),
                'replace' => array_shift(array_values($transform))
            );
        } else {
            $transformers = array_map('trim', explode(',', $transform));
            foreach ($transformers as $transformer) {
                $transformer = $this->transformers[$transformer];
                $infos[] = array(
                    'search' => array_shift(array_keys($transformer)),
                    'replace' => array_shift(array_values($transformer))
                );
            }
        }
        // Applies transformers to query value
        foreach ($infos as $info) {
            $value_transformed = preg_replace($info['search'], $info['replace'], $value, -1, $count);
            if ($value_transformed === null) throw new xException("Error transforming field value for field ({$field}), value ({$query})", 500);
            // If transformer worked, stop here
            // Otherwise, continue with next transformer
            if ($count) {
                $value = $value_transformed;
                break;
            }
        }
        return $value;
    }

    /**
     * Returns a structured query representation from fulltext string.
     *
     * The query representation consists in a set of keyword information.
     * Each keyword information contains:
     * - operator: the operator (AND, OR)
     * - value: the value (keyword)
     * - fieldname: the fieldname associated with the value (if speficied, null otherwise).
     *
     * For example, "some-value fieldname:some-other-value" would return the following structure.
     * <code>
     * array(
     *     array(
     *         'operator' => 'AND',
     *         'value' => 'some-value',
     *         'field' => null
     *     ),
     *     array(
     *         'operator' => 'AND',
     *         'value' => 'some-other-value',
     *         'field' => 'fieldname'
     *     ),
     *     ...
     * )
     * </code>
     * @return array
     */
    protected function get_query() {
        $q = @$this->params['xquery'];
        // Cleans query string operators ( ,:)
        // removing duplicates, leadings and trailings
        while (isset($count) ? $count : true) $q = preg_replace(
            array('/,,/', '/  /', '/, /', '/ ,/', '/::/', '/^\s|^,|\s$|,$/'),
            array(',',    ' ',    ',',    ',',    ':',    ''),
            $q,
            -1,
            $count
        );
        // Splits query string parts
        $parts = preg_split(
            '/(\s|\,)/',
            $q,
            -1,
            PREG_SPLIT_NO_EMPTY+PREG_SPLIT_DELIM_CAPTURE
        );
        // Creates a structured query representations
        $s = array();
        array_unshift($parts, ' ');
        for ($i=0; $i<count($parts); $i=$i+2) {
            $op = $parts[$i];
            $value = $parts[$i+1];
            $field = null;
            // Manages optional field prefix (eg. field:value)
            $value = explode(':', $value);
            if (count($value)>1) {
                // Case field prefix (eg. field:value)
                $field = $value[0];
                $value = $value[1];
            } else {
                // Case simple value (eg: value)
                $value = array_shift($value);
            }
            $s[] = array(
                'operator' => $this->operators[$op],
                'value' => $value,
                'field' => $field
            );
        }
        return $s;
    }

    /**
     * Returns an array of field to query on.
     *
     * Returns the configured fields in fields property,
     * minus the fields specified in parameters.
     * If fields property is empty, returns all possible fields (including joined fields),
     * minus the fields specified in parameters.
     *
     * @see $fields
     * @return array
     */
    protected function get_fields() {
        $model = xModel::load($this->model, $this->params);
        // Retrieve model fields names list (including foreign fields)
        $fields = array_merge(
            array_keys($model->mapping),
            array_keys($model->foreign_mapping())
        );
        // Keeps required fields only (if applicable)
        if ($this->fields) $fields = array_intersect($fields, $this->fields);
        // Discards fields existing in params: these are to be used as constraints
        // FIXME: Constraints are not taken into account yet (to be done)
        //$fields = array_diff($fields, array_keys($this->params));
        // Returns selected fields
        return $fields;
    }
}