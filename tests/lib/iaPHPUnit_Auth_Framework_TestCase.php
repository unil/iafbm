<?php

require_once(__DIR__.'/../../iafbm/lib/xfm/lib/Util/Auth.php');
require_once(__DIR__.'/../../iafbm/lib/iafbm/xfm/Auth.php');
require_once(__DIR__.'/../../iafbm/lib/xfm/lib/Core/Bootstrap.php');
require_once(__DIR__.'/../../iafbm/lib/iafbm/xfm/Bootstrap.php');

// See iaPHPUnit_Auth_Framework_TestCase docblock below for instructions

/**
 * Unittesing-specific Bootstrap.
 * Instanciates your custom Auth class as xContext::$auth.
 * @internal
 * @see iaPHPUnit_Auth_Framework_TestCase
 */
class iaTestAuthBootstrap extends Bootstrap {

    /**
     * Set at construct time.
     * Contains the running iaPHPUnit_Auth_Framework_TestCase child class name.
     */
    public $testClassName;

    /**
     * Overriden to store the name of the currently running test,
     * for custom Auth class setup.
     * @param string Profile name for xBootstrap.
     * @param string Classname of the running test.
     */
    function __construct($profile, $testClassName) {
        $this->testClassName = $testClassName;
        parent::__construct($profile);
    }

    function setup_auth() {
        $authClass = "{$this->testClassName}Auth";
        // Uses custom auth class
        xContext::$log->log("Setting up {$this->testClassName}", $this);
        xContext::$auth = new $authClass();
    }
}

/**
 * PHPUnit unittest class using a test-specific Auth class.
 *
 * How to use a custom Auth class for a given test:
 *  1. Make your PHPUnit test class extend iaPHPUnit_Auth_Framework_TestCase.
 *  2. define a custom Auth class whose name begins with your PHPUnit test class
 *     appended with Auth.
 *  3. That's it!
 *
 * Example:
 * Your iaPHPUnit_Auth_Framework_TestCase child classname is "MyAwesomeTest":
 * <code>
 * class MyAwesomeTestAuth extends iaAuth {
 *    // Refine test specific things here,
 *     // eg. the $permissions property.
 * }
 * </code>
 */
class iaPHPUnit_Auth_Framework_TestCase extends iaPHPUnit_Framework_TestCase {

    /**
     * This method is meant to be called on child classes
     * and return the child class name.
     */
    function testClassName() {
        return get_called_class();
    }

    function setUp() {
        new iaTestAuthBootstrap(null, $this->testClassName());
    }

    /**
     * Sets custom Shibboleth authentication for testing.
     * @param string Authenticated username.
     * @param string Organisation name for username@organisation user id creation.
     * @param string Semicolon-sepearated (;) roles.
     */
    protected function set_shibboleth($username, $org, $roles) {
        // Simulates Shibboleth server information
        $_SERVER['HTTP_SHIB_PERSON_UID'] = $username;
        $_SERVER['HTTP_SHIB_SWISSEP_HOMEORGANIZATION'] = $org;
        $_SERVER['HTTP_SHIB_CUSTOM_UNILMEMBEROF'] = $roles;
        // Reparses auth information
        xContext::$auth->set_from_aai();
    }
}