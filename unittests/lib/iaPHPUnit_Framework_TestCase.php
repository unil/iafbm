<?php

require_once(__dir__.'/../../iafbm/lib/xfm/unittests/lib/PHPUnit_Framework_TestCase.php');

/**
 * Custom PHPUnit_Framework_TestCase.
 * Sets up custom authentication information with 'local-superuser' role.
 * @package unittests-library
 */
class iaPHPUnit_Framework_TestCase extends xPHPUnit_Framework_TestCase
{

    function setup_bootstrap() {
        require_once(__dir__.'/../../iafbm/lib/iafbm/xfm/iaBootstrap.php');
        new iaBootstrap();
    }

    function setUp() {
        parent::setUp();
        // Sets a default auth information with all permissions
        $_SERVER['HTTP_SHIB_PERSON_UID'] = 'unit-tests';
        $_SERVER['HTTP_SHIB_SWISSEP_HOMEORGANIZATION'] = 'localhost';
        $_SERVER['HTTP_SHIB_CUSTOM_UNILMEMBEROF'] = 'local-superuser';
        xContext::$auth->set_from_aai();
    }

    function tearDown() {}

    function create($controller_name, $data) {
        return xController::load($controller_name, array(
            'items' => $data
        ));
    }
    function get($controller_name, $data) {
        $data = is_array($data) ? $data : array('id'=>$data);
        return xController::load($controller_name, $data)->get();
    }

    function dump() {
        print "\n";
        foreach(func_get_args() as $arg) {
            var_dump($arg);
            print "\n";
        }
    }
}

?>
