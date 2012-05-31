<?php

require_once(__DIR__.'/../../iafbm/lib/xfreemwork/lib/lib/Util/Auth.php');
require_once(__DIR__.'/../../iafbm/lib/iafbm/xfreemwork/Auth.php');
require_once(__DIR__.'/../../iafbm/lib/xfreemwork/lib/lib/Core/Bootstrap.php');
require_once(__DIR__.'/../../iafbm/lib/iafbm/xfreemwork/Bootstrap.php');

/**
 * Unittesing-specific iaAuth class.
 * Defines a static $permission.
 * @package unittests
 */
class iaTestAuth extends iaAuth {
    protected $permissions = array(
        'multiple , roles, canonalization' => array(
            'models' => array(
                'somemodel' => 'CRU'
            )
        ),
        'wildcards' => array(
            'models' => array(
                '*' => 'R',
                'candidat' => null
            )
        ),
    );

    function get_permissions() {
        return $this->permissions;
    }
}

/**
 * Unittesing-specific Bootstrap.
 * Setups iaTestAuth as xContext::$auth.
 */
class iaTestAuthBootstrap extends Bootstrap {
    function setup_auth() {
        xContext::$log->log("Setting up iaTestAuth", $this);
        xContext::$auth = new iaTestAuth();
    }
}

/**
 * Tests iaAuth class.
 */
class AuthenticationTest extends iaPHPUnit_Framework_TestCase {

    function setUp() {
        new iaTestAuthBootstrap();
    }

    protected function set_shibboleth($username, $org, $roles) {
        // Simulates Shibboleth server information
        $_SERVER['HTTP_SHIB_PERSON_UID'] = $username;
        $_SERVER['HTTP_SHIB_SWISSEP_HOMEORGANIZATION'] = $org;
        $_SERVER['HTTP_SHIB_CUSTOM_UNILMEMBEROF'] = $roles;
        // Reparses auth information
        xContext::$auth->set_from_aai();
    }

    function test_auth_info_from_shibboleth() {
        $auth = xContext::$auth;
        // Auth test-data set
        $shib_username = 'username';
        $shib_org = 'organisation';
        $shib_roles = 'fbm-iafbm-g ; abc;def; hij;klm ';
        // Simulates Shibboleth server information
        $this->set_shibboleth($shib_username, $shib_org, $shib_roles);
        // Reparses auth information
        xContext::$auth->set_from_aai();
        // Tests stored auth information
        $username = "{$shib_username}@{$shib_org}";
        $roles = array_map('trim', explode(';', $shib_roles));
        $this->assertSame($username, $auth->username());
        $this->assertSame($roles, $auth->roles());
    }

    function test_canonalization_multiple_roles() {
        $auth = xContext::$auth;
        $permissions = $auth->get_permissions();
        // Only keeps roles to test (eg. it canonalizes)
        $permissions = xUtil::filter_keys($permissions, array('multiple', 'roles', 'canonalization'));
        foreach ($permissions as $role => $info) {
            $models = $info['models'];
            foreach ($models as $model => $operations) {
                $this->assertSame('somemodel', $model);
                $this->assertSame('CRU', $operations);
            }
        }
    }

    function test_canonalization_models_wildcards() {
        // Creates expected permissions
        $expected = array();
        foreach (xModel::scan() as $model) {
            // Every model is 'get' allowed, except candidat
            $expected[$model] = ($model != 'candidat') ? 'R' : null;
        }
        // Actual test
        $auth = xContext::$auth;
        $permissions = $auth->get_permissions();
        // Only keeps role to test (eg. it has a wildcard)
        $permissions = xUtil::filter_keys($permissions, array('wildcards'));
        foreach ($permissions as $role => $info) {
            $models = $info['models'];
            foreach ($models as $model => $operations) {
                $this->assertSame(
                    $expected[$model],
                    $operations,
                    "Model $model"
                );
            }
        }
    }
}