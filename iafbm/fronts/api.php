<?php

class ApiFront extends xApiFront {


    function __construct($params = null) {
        parent::__construct($params);
        // Sets the called method according the HTTP Request Verb if no method specified
        if (!@$this->params['xmethod']) $this->params['xmethod'] = @$this->http['method'];
        $this->prepare();
    }

    /**
     * Merges the HTTP Request body paramters with the the instance parameters.
     */
    function prepare() {
        // Merges HTTP Request body with the instance parameters
        $body = $this->get_request_body();
        $params = $this->decode($body);
        // Removes JSON root cell post+get requests (According Ext.data.JsonWriter behaviour)
        if (in_array(strtolower($this->params['xmethod']), array('put', 'post'))) {
            $params = @array_shift($params);
        }
        $this->params = xUtil::array_merge($this->params, xUtil::arrize($params));
    }

    function get() {
        $result = $this->call_method();
        print $this->encode($result);
    }

    function post() {
        $result = $this->call_method();
        print $this->encode($result);
    }

    function put() {
        $result = $this->call_method();
        print $this->encode($result);
    }

    function delete() {
        $result = $this->call_method();
        print $this->encode($result);
    }


}