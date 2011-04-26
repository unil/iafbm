<?php

class ApiFront extends xApiFront {

    /**
     * @see xFront::$method_mapping
     */
    var $method_mapping = array(
        'GET' => 'get',
        'POST' => 'get',
        'PUT' => 'post',
        'DELETE' => 'delete'
    );

    function __construct($params = null) {
        parent::__construct($params);
        // Sets the called method according the HTTP Request Verb if no method specified
        if (!@$this->params['xmethod']) $this->params['xmethod'] = @$this->http['method'];
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