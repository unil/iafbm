<?php

class ApiFront extends xApiFront {

    /**
     * Merges the HTTP Request body paramters with the object parameters.
     */
    function prepare() {
        $this->params['xmethod'] = $this->http['method'];
        $body = $this->get_request_body();
        $params = $this->decode($body);
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