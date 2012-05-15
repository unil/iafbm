<?php

class UserController extends iaWebController {

    function defaultAction() {
        return $this->detailAction();
    }

    function detailAction() {
        $data = array(
            'username' => xContext::$auth->username(),
            'identity' => array(
                'name' => @$_SERVER['givenName'],
                'surname' => @$_SERVER['surname'],
                'email' => @$_SERVER['mail'],
                'org' => @$_SERVER['homeOrganization'],
                'affiliation' => @$_SERVER['affiliation']
            ),
            'roles' => xContext::$auth->roles(),
            'permissions' => xContext::$auth->user_permissions(),
            'versions' => array(
                'total' => xModel::load('version')->count(),
                'count' => xModel::load('version', array(
                    'creator' => xContext::$auth->username()
                ))->count(),
                'first' => xModel::load('version', array(
                    'creator' => xContext::$auth->username(),
                    'xorder_by' => 'created',
                    'xorder' => 'ASC',
                    'xlimit' => 1
                ))->get(0),
                'last' => xModel::load('version', array(
                    'creator' => xContext::$auth->username(),
                    'xorder_by' => 'created',
                    'xorder' => 'DESC',
                    'xlimit' => 1
                ))->get(0)
            ),
            'modifications' => array(
                'total' => xModel::load('version_data')->count(),
                'count' => xModel::load('version_data', array(
                    'version_creator' => xContext::$auth->username()
                ))->count()
            )
        );
        return xView::load('user/detail', $data)->render();
    }

}