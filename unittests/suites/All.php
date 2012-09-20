<?php

// From: http://parthpatil.com/2008/05/14/formatting-phpunit-test-results-as-html-table/

// PHPUnit Library
require_once('PHPUnit/Framework/TestSuite.php');
require_once('PHPUnit/Framework/TestResult.php');
require_once('PHPUnit/Util/Log/XML.php');

// Units
require_once('ArrayTest.php');
 
class MyTestRunner
{
    public static function run()
    {
        // Create the test suite instance
        $suite = new PHPUnit_Framework_TestSuite();
        $suite->setName('MyTestRunner');
 
        // Add files to the TestSuite
        $suite->addTestSuite('ArrayTest');
 
        // Create a xml listener object 
        $listener = new PHPUnit_Util_Log_XML;
 
        // Create TestResult object and pass the xml listener to it
        $testResult = new PHPUnit_Framework_TestResult();
        $testResult->addListener($listener);
 
        // Run the TestSuite
        $result = $suite->run($testResult);
 
        // Get the results from the listener
        $xml_result = $listener->getXML();
        return $xml_result;
    }
}
