<?php

class UserController extends iaWebController {

    function defaultAction() {
        return $this->detailAction();
    }

    function detailAction() {
        $data = array(
            'username' => xContext::$auth->username(),
            'roles' => xContext::$auth->roles()
        );
        return xView::load('user/detail', $data)->render();
    }

}