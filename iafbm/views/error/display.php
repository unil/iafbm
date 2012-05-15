<?php

class ErrorDisplayView extends xView {

    function init() {
        // Messages definition
        $this->msgs = array(
            400 => _('The data you provided is not correct'),
            401 => _('You are not allowed to access this resource'),
            403 => _('You are not allowed to access this resource'),
            404 => _('The page you requested was not found'),
            500 => _('An unexpected error happened'),
            'default' => _('An unknown error happened')
        );
        // Page titles definition
        $this->titles = array(
            404 => _('Page not found'),
            'default' => _('Error')
        );
        // Default exception status setup
        $status = isset($this->data['exception']->status) ? $this->data['exception']->status : 500;
        // Page title setup
        $this->meta['title'] = @$this->titles[$status] ? $this->titles[$status] : $this->titles['default'];
        // Setup view data
        $this->data['previous_page'] = xWebFront::previous_url();
        $this->data['msg'] = @$this->msgs[$status] ? $this->msgs[$status] : $this->msgs['default'];
    }
}

?>
