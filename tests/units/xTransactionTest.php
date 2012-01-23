<?php

/**
 * Tests versioning feature through xModels (bypasses xControllers complexity)
 *
 * TODO:
 * - Test versioning robustness to errors, eg:
 *    - the controller starts a transaction, an error occurs within the transaction, no version should not be written (case: add personne_adresse)
 */
class xTransactionTest extends iaPHPUnit_Framework_TestCase
{

    function create($controller_name, $data) {
        return xController::load($controller_name, array(
            'items' => $data
        ));
    }
    function get($controller_name, $data) {
        $data = is_array($data) ? $data : array('id'=>$data);
        return xController::load($controller_name, $data)->get();
    }

    function _test_atomic_pass() {
        $t = new xTransaction();
        $initial_commit_state = $t->autocommit();
        $t->start();
        $this->assertEquals($t->autocommit(), 0);
        $r = $t->execute_sql('SELECT * FROM personnes');
        $this->assertTrue(is_resource($r));
        $t->end();
        $this->assertEquals($t->autocommit(), $initial_commit_state);
    }

    /**
     * @expectedException xException
     * @expectedExceptionMessage 1 operation(s) failed during the transaction
     */
    function test_atomic_fail() {
        $t = new xTransaction();
        $t->start();
        $t->execute_sql('SELECT * FROM unknown_table');
        $t->end();
    }

    function test_nested_pass() {
        $t1 = new xTransaction();
        $initial_commit_state = $t1->autocommit();
        ## Transactions count = 0
        $this->assertEquals($t1::$started_transactions_count, 0);
        // Top level transaction (1)
        $t1->start();
        ## Autocommit = 0
        $this->assertEquals($t1->autocommit(), 0);
        ## Transactions count = 1
        $this->assertEquals($t1::$started_transactions_count, 1);
        $r = $t1->execute_sql('SELECT * FROM personnes LIMIT 1');
        ## Result is a ressource
        $this->assertTrue(is_resource($r));
        // Nested transaction (2)
        $t2 = new xTransaction();
        $t2->start();
        ## Autocommit = 0
        $this->assertEquals($t1->autocommit(), 0);
        ## Transactions count = 2
        $this->assertEquals($t1::$started_transactions_count, 2);
        $r = $t2->execute_sql('SELECT * FROM personnes LIMIT 1');
        ## Result is a ressource
        $this->assertTrue(is_resource($r));
        $r = $t2->execute_sql('SELECT * FROM personnes LIMIT 1');
        ## Result is a ressource
        $this->assertTrue(is_resource($r));
        $summary2 = $t2->end();
        ## $t2 summary contains 2 results
        $this->assertEquals(count($summary2['xresults']), 2);
        ## Autocommit = 0
        $this->assertEquals($t1->autocommit(), 0);
        ## Transactions count = 1
        $this->assertEquals($t1::$started_transactions_count, 1);
        $summary1 = $t1->end();
        ## $t1 summary contains 1 result
        $this->assertEquals(count($summary1['xresults']), 1);
        ## Transactions count = 0
        $this->assertEquals($t1::$started_transactions_count, 0);
        ## Autocommit = initial autocommit state
        $this->assertEquals($t1->autocommit(), $initial_commit_state);
    }

    /**
     * @expectedException xException
     * @expectedExceptionMessage 2 operation(s) failed during the transaction
     */
    function test_nested_fail() {
        $t1 = new xTransaction();
        $initial_commit_state = $t1->autocommit();
        ## Transactions count = 0
        $this->assertEquals($t1::$started_transactions_count, 0);
        // Top level transaction (1)
        $t1->start();
        ## Autocommit = 0
        $this->assertEquals($t1->autocommit(), 0);
        ## Transactions count = 1
        $this->assertEquals($t1::$started_transactions_count, 1);
        $r = $t1->execute_sql('SELECT * FROM unknown_table');
        ## Result is NOT a ressource
        $this->assertTrue(!is_resource($r));
        // Nested transaction (2)
        $t2 = new xTransaction();
        $t2->start();
        ## Autocommit = 0
        $this->assertEquals($t1->autocommit(), 0);
        ## Transactions count = 2
        $this->assertEquals($t1::$started_transactions_count, 2);
        $r = $t2->execute_sql('SELECT * FROM unknown_table');
        ## Result is NOT a ressource
        $this->assertTrue(!is_resource($r));
        $r = $t2->execute_sql('SELECT * FROM unknown_table');
        ## Result is NOT a ressource
        $this->assertTrue(!is_resource($r));
        try {
            $t2->end();
        } catch (Exception $e) {
            ## $t2 exception contains only its errors (2)
            $this->assertEquals(count($e->data['exceptions']), 2);
            throw ($e);
        }
        ## Autocommit = 0
        $this->assertEquals($t1->autocommit(), 0);
        ## Transactions count = 1
        $this->assertEquals($t1::$started_transactions_count, 1);
        $t1->end();
        ## Transactions count = 0
        $this->assertEquals($t1::$started_transactions_count, 0);
        ## Autocommit = initial autocommit state
        $this->assertEquals($t1->autocommit(), $initial_commit_state);
    }
}