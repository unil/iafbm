<?php

/**
 * Tests xTransaction class.
 * Test are made at xModel level.
 */
class xTransactionTest extends iaPHPUnit_Framework_TestCase {

    function test_atomic_pass() {
        $f = __FUNCTION__;
        $t = new xTransaction();
        ## Transactions count = 0
        $this->assertEquals(0, $t::$started_transactions_count);
        $initial_commit_state = $t->autocommit();
        $t->start();
        ## Autocommit is set to 0
        $this->assertEquals(0, $t->autocommit());
        $r = $t->execute(xModel::load('personne', array(
            'nom' => "Un nom ($f)",
            'prenom' => "Un prénom ($f)"
        )), 'put');
        ## Operation result is well-formed
        $this->assertEquals(1, $r['xaffectedrows']);
        $this->assertTrue($r['xsuccess']);
        $id = $r['xinsertid'];
        $r = xModel::load('personne', array('id'=>$id))->get();
        ## Row is accessible within the transaction (not committed yet)
        $this->assertCount(1, $r);
        $r = $t->end();
        ## Autocommit is reset to initial autocommit state
        $this->assertEquals($initial_commit_state, $t->autocommit());
        ## Transaction result is well-formed
        $this->assertTrue($r['xsuccess']);
        $this->assertEquals(1, $r['xaffectedrows']);
        $this->assertCount(1, $r['xresults']);
        $r = xModel::load('personne', array('id'=>$id))->get();
        ## Row is inserted into database table
        $this->assertCount(1, $r);
    }

    function test_atomic_fail_select() {
        $t = new xTransaction();
        ## Transactions count = 0
        $this->assertEquals(0, $t::$started_transactions_count);
        $t->start();
        $r = $t->execute_sql('SELECT * FROM unknown_table');
        # Returns an exception
        $this->assertTrue($r instanceof xException);
        try {
            $t->end();
        } catch (Exception $e) {
            $this->assertTrue($e instanceof xException);
            $this->assertEquals($e->status, 500);
            $this->assertEquals('1 operation(s) failed during the transaction', $e->getMessage());
        }
    }

    /**
     * @expectedException xException
     * @expectedExceptionMessage 1 operation(s) failed during the transaction
     */
    function _test_atomic_fail_insert() {
        $f = __FUNCTION__;
        $t = new xTransaction();
        ## Transactions count = 0
        $this->assertEquals(0, $t::$started_transactions_count);
        $t->start();
        ## Transactions count is increased (1)
        $this->assertEquals(1, $t::$started_transactions_count);
        $r = $t->execute(xModel::load('personne', array(
            'nom' => "",
            'prenom' => "Un prénom ($f)"
        )), 'put');
        # Returns an exception
        $this->assertTrue($r instanceof xException);
        try {
            $t->end();
        } catch (Exception $e) {
            $this->assertTrue($e instanceof xException);
            $this->assertEquals(500, $e->status);
            $this->assertEquals('1 operation(s) failed during the transaction', $e->getMessage());
        }
    }

    function test_atomic_rollback_pass() {
        $t = new xTransaction();
        ## Transactions count = 0
        $this->assertEquals(0, $t::$started_transactions_count);
        $initial_commit_state = $t->autocommit();
        $t->start();
        ## Transactions count is increased (1)
        $this->assertEquals(1, $t::$started_transactions_count);
        ## Autocommit is set to 0
        $this->assertEquals(0, $t->autocommit());
        $r = $t->execute(xModel::load('personne', array(
            'nom' => 'Un nom (should not be inserted)',
            'prenom' => 'Un prénom (should not be inserted)'
        )), 'put');
        ## Operation result is well-formed
        $this->assertEquals(1, $r['xaffectedrows']);
        $this->assertTrue($r['xsuccess']);
        $id = $r['xinsertid'];
        $r = xModel::load('personne', array('id'=>$id))->get();
        ## Row is accessible within the transaction (not committed yet)
        $this->assertCount(1, $r);
        $r = $t->rollback();
        ## Transactions count is reset (0)
        $this->assertEquals(0, $t::$started_transactions_count);
        ## Autocommit is reset to initial autocommit state
        $this->assertEquals($initial_commit_state, $t->autocommit());
        $r = xModel::load('personne', array('id'=>$id))->get();
        ## Row is not inserted after rollback
        $this->assertCount(0, $r);
        $r = $t->end();
        ## Transactions count is reset (0)
        $this->assertEquals(0, $t::$started_transactions_count);
        ## For now, rollback returns successes
        $this->assertTrue($r['xsuccess']);
        $this->assertEquals(1, $r['xaffectedrows']);
        $this->assertCount(1, $r['xresults']);
    }

    function test_nested_pass() {
        $t1 = new xTransaction();
        ## Transactions count = 0
        $this->assertEquals(0, $t1::$started_transactions_count);
        $initial_commit_state = $t1->autocommit();
        // Top level transaction (1)
        $t1->start();
        ## Autocommit is set to 0
        $this->assertEquals(0, $t1->autocommit());
        ## Transactions count is increased (1)
        $this->assertEquals(1, $t1::$started_transactions_count);
        $r = $t1->execute_sql('SELECT * FROM personnes LIMIT 1');
        ## Result is a ressource
        $this->assertTrue(is_resource($r));
        // Nested transaction (2)
        $t2 = new xTransaction();
        $t2->start();
        ## Autocommit stays at 0
        $this->assertEquals(0, $t1->autocommit());
        ## Transactions count is increased (2)
        $this->assertEquals(2, $t1::$started_transactions_count);
        $r = $t2->execute_sql('SELECT * FROM personnes LIMIT 1');
        ## Result is a ressource
        $this->assertTrue(is_resource($r));
        $r = $t2->execute_sql('SELECT * FROM personnes LIMIT 1');
        ## Result is a ressource
        $this->assertTrue(is_resource($r));
        $summary2 = $t2->end();
        ## $t2 summary contains 2 results
        $this->assertCount(2, $summary2['xresults']);
        ## Autocommit stays at 0
        $this->assertEquals(0, $t1->autocommit());
        ## Transactions count is decreased (1)
        $this->assertEquals(1, $t1::$started_transactions_count);
        $summary1 = $t1->end();
        ## $t1 summary contains 1 result
        $this->assertCount(1, $summary1['xresults']);
        ## Transactions count is decreased (0)
        $this->assertEquals(0, $t1::$started_transactions_count);
        ## Autocommit is reset to initial autocommit state
        $this->assertEquals($initial_commit_state, $t1->autocommit());
    }

    function test_nested_fail_1st() {
        $t1 = new xTransaction();
        $initial_commit_state = $t1->autocommit();
        ## Transactions count = 0
        $this->assertEquals(0, $t1::$started_transactions_count);
        // Top level transaction (1)
        $t1->start();
        ## Autocommit is set to 0
        $this->assertEquals(0, $t1->autocommit());
        ## Transactions count is increased (1)
        $this->assertEquals(1, $t1::$started_transactions_count);
        $r = $t1->execute_sql('SELECT * FROM unknown_table');
        ## Result is NOT a ressource
        $this->assertTrue(!is_resource($r));
        // Nested transaction (2)
        $t2 = new xTransaction();
        $t2->start();
        ## Autocommit stays at 0
        $this->assertEquals(0, $t1->autocommit());
        ## Transactions count is increased (2)
        $this->assertEquals(2, $t1::$started_transactions_count);
        $r = $t2->execute_sql('SELECT * FROM personnes');
        ## Result is a ressource
        $this->assertTrue(is_resource($r));
        $r = $t2->execute_sql('SELECT * FROM personnes');
        ## Result is a ressource
        $this->assertTrue(is_resource($r));
        $t2->end();
        ## Autocommit stays at 0
        $this->assertEquals(0, $t1->autocommit());
        ## Transactions count is decreased (1)
        $this->assertEquals(1, $t1::$started_transactions_count);
        try {
            $t1->end();
        } catch (Exception $e) {
            ## $t1 exception contains only its errors (1)
            $this->assertTrue($e instanceof xException);
            $this->assertEquals('1 operation(s) failed during the transaction', $e->getMessage());
            $this->assertEquals(500, $e->status);
            $this->assertCount(1, $e->data['exceptions']);
        }
        ## Transactions count is reset
        $this->assertEquals(0, $t1::$started_transactions_count);
        ## Autocommit is reset to initial autocommit state
        $this->assertEquals($initial_commit_state, $t1->autocommit());
    }

    function test_nested_fail_2nd() {
        $t1 = new xTransaction();
        $initial_commit_state = $t1->autocommit();
        ## Transactions count = 0
        $this->assertEquals(0, $t1::$started_transactions_count);
        // Top level transaction (1)
        $t1->start();
        ## Autocommit is set to 0
        $this->assertEquals($t1->autocommit(), 0);
        ## Transactions count = 1
        $this->assertEquals(1, $t1::$started_transactions_count);
        $r = $t1->execute_sql('SELECT * FROM unknown_table');
        ## Result is NOT a ressource
        $this->assertTrue(!is_resource($r));
        // Nested transaction (2)
        $t2 = new xTransaction();
        $t2->start();
        ## Autocommit stays at 0
        $this->assertEquals(0, $t1->autocommit());
        ## Transactions count = 2
        $this->assertEquals(2, $t1::$started_transactions_count);
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
            $this->assertTrue($e instanceof xException);
            $this->assertEquals('2 operation(s) failed during the transaction', $e->getMessage());
            $this->assertEquals(500, $e->status);
            $this->assertCount(2, $e->data['exceptions']);
        }
        ## Transactions count is reset
        $this->assertEquals(0, $t1::$started_transactions_count);
        ## Autocommit is reset to initial autocommit state
        $this->assertEquals($initial_commit_state, $t1->autocommit());
    }

    function test_nested_with_rollback_within_nested() {
        // TODO
    }

    function test_outer_transactions_prevention() {
        $t = new xTransaction();
        $initial_commit_state = $t->autocommit();
        $t->start();
        $t->execute_sql('SELECT * FROM personnes');
        $t->execute(xModel::load('personne', array('xlimit'=>1)), 'get');
        $r = $t->end();
        # Outer call to execute_sql throws an exception
        try {
            $t->execute_sql('SELECT * FROM personnes');
        } catch (Exception $e) {
            $this->assertTrue($e instanceof xException);
            $this->assertEquals('Cannot execute a statement if no transaction in progress', $e->getMessage());
            $this->assertEquals(500, $e->status);
        }
        # Outer call to execute_model throws an exception
        try {
            $t->execute('personne', array('xlimit'=>1), 'get');
        } catch (Exception $e) {
            $this->assertTrue($e instanceof xException);
            $this->assertEquals('Cannot execute a statement if no transaction in progress', $e->getMessage());
            $this->assertEquals(500, $e->status);
        }
        # Summary is not changes by outer requests
        $this->assertEquals($r, $t->summary());
        ## Autocommit is reset to initial autocommit state
        $this->assertEquals($initial_commit_state, $t->autocommit());
        $t->start();
        ## Autocommit = 0
        $this->assertEquals(0, $t->autocommit());
        $this->assertEquals(1, $t::$started_transactions_count);
        $this->assertEquals(1, $t::$autocommit_state_backup);
        $this->assertCount(0, $t->results);
        $this->assertCount(0, $t->exceptions);
    }
}