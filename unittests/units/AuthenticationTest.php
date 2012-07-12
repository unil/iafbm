<?php

require_once(__DIR__.'/../lib/iaPHPUnit_Auth_Framework_TestCase.php');

/**
 * Unittesing-specific iaAuth class.
 * Defines a custom $permission.
 * @package unittests
 */
class AuthenticationTestAuth extends iaAuth {
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

    function get_permissions_property() {
        return $this->permissions;
    }
}

/**
 * Tests iaAuth class.
 */
class AuthenticationTest extends iaPHPUnit_Auth_Framework_TestCase {

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
        $permissions = $auth->get_permissions_property();
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
        $permissions = $auth->get_permissions_property();
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