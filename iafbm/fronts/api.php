<?php

class ApiFront extends xApiFront {


    function __construct($params = null) {
        parent::__construct($params);
        $this->prepare();
    }

    /**
     * Merges the HTTP Request body paramters with the the instance parameters.
     */
    function prepare() {
        // Sets the called method according the HTTP Request Verb
        // if no method specified
        if (!@$this->params['xmethod']) $this->params['xmethod'] = @$this->http['method'];
        // Merges HTTP Request body with the instance parameters
        $body = $this->get_request_body();
        $params = $this->decode($body);
        $params = @array_shift($params); // Removes JSON root cell: this will not work all the time and should be used only for post+get requests
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