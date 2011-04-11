<?php

class ApiFront extends xApiFront {

    /**
     * Merges the HTTP Request body paramters with the the instance parameters.
     */
    function prepare() {
        // Sets the called method according the HTTP Request Verb
//        $this->params['xmethod'] = $this->http['method'];
        // Merges HTTP Request body with the instance parameters
        $body = $this->get_request_body();
        $params = $this->decode($body);
        $params = array_shift($params); // Removes JSON root cell
        $this->params = xUtil::array_merge($this->params, $params);
    }

    function get() {
        $this->prepare();
        $result = $this->call_method();
        print $this->encode($result);
    }

    function post() {
        $this->prepare();
        $result = $this->call_method();
        print $this->encode($result);
    }

    function put() {
        $this->prepare();
        $result = $this->call_method();
        print $this->encode($result);
    }

    function delete() {
        $this->prepare();
        $result = $this->call_method();
        print $this->encode($result);
    }


}