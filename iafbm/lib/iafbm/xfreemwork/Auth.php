<?php

class iaAuth extends xAuth {

    protected $role_separator = ';';

    function set_from_aai() {
        $authenticated = isset(
            $_SERVER['HTTP_SHIB_PERSON_UID'],
            $_SERVER['HTTP_SHIB_SWISSEP_HOMEORGANIZATION']
        );
        $username = $authenticated ? implode(
            '@',
            array(
                $_SERVER['HTTP_SHIB_PERSON_UID'],
                $_SERVER['HTTP_SHIB_SWISSEP_HOMEORGANIZATION']
            )
        ) : 'guest';
        $roles = @$_SERVER['HTTP_SHIB_CUSTOM_UNILMEMBEROF'];
        $this->set($username, $roles);
    }

}