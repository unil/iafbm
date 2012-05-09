<?php

/**
 * Tests iaAuth class.
 */
class AuthTest extends iaPHPUnit_Framework_TestCase {

    protected $shib_username = 'username';
    protected $shib_org = 'organisation';
    protected $shib_roles = 'fbm-iafbm-g';

    /**
     * @see iaPHPUnit_Framework_TestCase::setUp()
     */
    function setUp() {
        // Simulates Shibboleth server information
        $_SERVER['HTTP_SHIB_PERSON_UID'] = $this->shib_username;
        $_SERVER['HTTP_SHIB_SWISSEP_HOMEORGANIZATION'] = $this->shib_org;
        $_SERVER['HTTP_SHIB_CUSTOM_UNILMEMBEROF'] = $this->shib_roles;
        // Parent setUp() creates Bootstrap and parses auth information
        parent::setUp();
        // Reparses auth information
        xContext::$auth->set_from_aai();
    }

    function test_auth_info_from_shibboleth() {
        $auth = xContext::$auth;
        $username = "{$this->shib_username}@{$this->shib_org}";
        $roles = array_map('trim', explode(';', $this->shib_roles));
        $this->assertSame($username, $auth->username());
        $this->assertSame($roles, $auth->roles());
    }

    function test_permissions_allroles() {
        $permissions = array();
        foreach (xModel::scan() as $model) {
            $permissions[$model] = array('get', 'put', 'post', 'delete');
        }
        $this->do_test_permissions(
            'fbm-iafbm-g;fbm-iafbm-releve-g;fbm-iafbm-admin-g',
            $permissions
        );
    }

    function test_permissions_fbm_iafbm_g() {
        $permissions = array();
        foreach (xModel::scan() as $model) {
            if ($model == 'candidat') continue;  // Candidat model is disallowed
            $permissions[$model] = array('get'); // Every other model is get allowed
        }
        $this->do_test_permissions(
            'fbm-iafbm-g',
            $permissions
        );
    }

    function test_permissions_fbm_iafbm_releve_g() {
        $permissions = array();
        foreach (xModel::scan() as $model) {
            $permissions[$model] = array('get', 'put', 'post', 'delete');
        }
        $this->do_test_permissions(
            'fbm-iafbm-releve-g',
            $permissions
        );
    }

    /**
     * Tests permissions on all models and operations combinaison,
     * according the given $roles end expected $permissions.
     * @param string Semicolon separated roles.
     * @param array Expected permissions per model (eg: 'modelname' => array('get', 'put', 'post'))
     */
    protected function do_test_permissions($roles, $permissions) {
        $auth = xContext::$auth;
        $ops = array('get', 'put', 'post', 'delete');
        // Simulates Shibboleth authentication
        $_SERVER['HTTP_SHIB_CUSTOM_UNILMEMBEROF'] = $roles;
        $auth->set_from_aai();
        // Asserts allowed/disallowed operations per model
        foreach ($permissions as $model => $operations) {
            // Allowed operations
            foreach ($operations as $operation) {
                $this->assertAllowed($model, $operation);
            }
            // Disallowed operations
            foreach (array_diff($ops, $operations) as $operation) {
                $this->assertDisallowed($model, $operation);
            }
        }
        // Disallowed models (those are not in $permissions
        // and all operations should be disallowed
        $models = array_diff(xModel::scan(), array_keys($permissions));
        foreach ($models as $model) {
            foreach ($ops as $operation) {
                $this->assertDisallowed($model, $operation);
            }
        }

    }

    /**
     * Asserts that the given $model is allowed to execute $operation.
     * @param string The model name.
     * @param array The expected allowed operation.
     */
    protected function assertAllowed($model, $operation) {
        $roles = implode(', ', xContext::$auth->roles());
        $this->assertTrue(
            xContext::$auth->is_allowed_model($model, $operation),
            "Failed asserting that {$model}:{$operation} is allowed (role: {$roles})"
        );
    }

    /**
     * Asserts that the given $model is disallowed to execute $operation.
     * @param string The model name.
     * @param array The expected disallowed operation.
     */
    protected function assertDisallowed($model, $operation) {
        $roles = implode(', ', xContext::$auth->roles());
        $this->assertFalse(
            xContext::$auth->is_allowed_model($model, $operation),
            "Failed asserting that {$model}:{$operation} is disallowed (role: {$roles})"
        );
    }
}