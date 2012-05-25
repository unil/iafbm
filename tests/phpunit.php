<?php
/* PHPUnit
 *
 * Copyright (c) 2001-2012, Sebastian Bergmann <sebastian@phpunit.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

define('PHPUnit_MAIN_METHOD', 'PHPUnit_TextUI_Command::main');

// Setups PHPUnit libs paths
$phpunit = '../iafbm/lib/PhpUnit';
$pear = '../iafbm/lib/PEAR';
set_include_path(get_include_path() . PATH_SEPARATOR . $pear);
set_include_path(get_include_path() . PATH_SEPARATOR . $phpunit);

// PHPUnit Autoload
require "{$phpunit}/PHPUnit/Autoload.php";

// Custom PHPUnit_Framework_TestCase
class iaPHPUnit_Framework_TestCase extends PHPUnit_Framework_TestCase
{

    function setUp() {
        require_once('../iafbm/public/Bootstrap.php');
        new Bootstrap();
        // Sets a default auth information with all permissions
        $_SERVER['HTTP_SHIB_PERSON_UID'] = 'unit-tests';
        $_SERVER['HTTP_SHIB_SWISSEP_HOMEORGANIZATION'] = 'localhost';
        $_SERVER['HTTP_SHIB_CUSTOM_UNILMEMBEROF'] = 'local-superuser';
        xContext::$auth->set_from_aai();
    }
    function tearDown() {
    }

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

// PHPUnit autorun
PHPUnit_TextUI_Command::main();

?>
