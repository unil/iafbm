<?php

/**
 * Tests versioning feature through xModels (bypasses xControllers complexity)
 */
class xTransactionTest extends iaPHPUnit_Framework_TestCase {

    function test_atomic_pass() {
        $f = __FUNCTION__;
        $t = new xTransaction();
        $initial_commit_state = $t->autocommit();
        $t->start();
        ## Autocommit is set to 0
        $this->assertEquals($t->autocommit(), 0);
        $r = $t->execute(xModel::load('personne', array(
            'nom' => "Un nom ($f)",
            'prenom' => "Un prénom ($f)"
        )), 'put');
        ## Operation result is well-formed
        $this->assertEquals($r['xaffectedrows'], 1);
        $this->assertTrue($r['xsuccess']);
        $id = $r['xinsertid'];
        $r = xModel::load('personne', array('id'=>$id))->get();
        ## Row is accessible within the transaction (not committed yet)
        $this->assertCount(1, $r);
        $r = $t->end();
        ## Autocommit is reset to initial autocommit state
        $this->assertEquals($t->autocommit(), $initial_commit_state);
        ## Transaction result is well-formed
        $this->assertTrue($r['xsuccess']);
        $this->assertEquals($r['xaffectedrows'], 1);
        $this->assertCount(1, $r['xresults']);
        $r = xModel::load('personne', array('id'=>$id))->get();
        ## Row is inserted into database table
        $this->assertCount(1, $r);
    }

    /**
     * @expectedException xException
     * @expectedExceptionMessage 1 operation(s) failed during the transaction
     */
    function test_atomic_fail_select() {
        $t = new xTransaction();
        $t->start();
        $r = $t->execute_sql('SELECT * FROM unknown_table');
        # Returns an exception
        $this->assertTrue($r instanceof xException);
        $t->end();
    }

    /**
     * @expectedException xException
     * @expectedExceptionMessage 1 operation(s) failed during the transaction
     */
    function test_atomic_fail_insert() {
        $f = __FUNCTION__;
        $t = new xTransaction();
        $t->start();
        $r = $t->execute(xModel::load('personne', array(
            'nom' => "",
            'prenom' => "Un prénom ($f)"
        )), 'put');
        # Returns an exception
        $this->assertTrue($r instanceof xException);
        $r = $t->end();
    }

    function test_atomic_rollback_pass() {
        $t = new xTransaction();
        $initial_commit_state = $t->autocommit();
        $t->start();
        ## Autocommit is set to 0
        $this->assertEquals($t->autocommit(), 0);
        $r = $t->execute(xModel::load('personne', array(
            'nom' => 'Un nom',
            'prenom' => 'Un prénom'
        )), 'put');
        ## Operation result is well-formed
        $this->assertEquals($r['xaffectedrows'], 1);
        $this->assertTrue($r['xsuccess']);
        $id = $r['xinsertid'];
        $r = xModel::load('personne', array('id'=>$id))->get();
        ## Row is accessible within the transaction (not committed yet)
        $this->assertCount(1, $r);
        $r = $t->rollback();
        ## Autocommit is reset to initial autocommit state
        $this->assertEquals($t->autocommit(), $initial_commit_state);
        $r = xModel::load('personne', array('id'=>$id))->get();
        ## Row is not inserted after rollback
        $this->assertCount(0, $r);
        $r = $t->end();
        ## For now, rollback returns successes
        $this->assertTrue($r['xsuccess']);
        $this->assertEquals($r['xaffectedrows'], 1);
        $this->assertCount(1, $r['xresults']);
    }

    function test_atomic_rollback_fail() {
    }

    function test_nested_pass() {
        $t1 = new xTransaction();
        $initial_commit_state = $t1->autocommit();
        ## Transactions count = 0
        $this->assertEquals($t1::$started_transactions_count, 0);
        // Top level transaction (1)
        $t1->start();
        ## Autocommit is set to 0
        $this->assertEquals($t1->autocommit(), 0);
        ## Transactions count is increased (1)
        $this->assertEquals($t1::$started_transactions_count, 1);
        $r = $t1->execute_sql('SELECT * FROM personnes LIMIT 1');
        ## Result is a ressource
        $this->assertTrue(is_resource($r));
        // Nested transaction (2)
        $t2 = new xTransaction();
        $t2->start();
        ## Autocommit stays at 0
        $this->assertEquals($t1->autocommit(), 0);
        ## Transactions count is increased (2)
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
        ## Autocommit stays at 0
        $this->assertEquals($t1->autocommit(), 0);
        ## Transactions count is decreased (1)
        $this->assertEquals($t1::$started_transactions_count, 1);
        $summary1 = $t1->end();
        ## $t1 summary contains 1 result
        $this->assertEquals(count($summary1['xresults']), 1);
        ## Transactions count is decreased (0)
        $this->assertEquals($t1::$started_transactions_count, 0);
        ## Autocommit is reset to initial autocommit state
        $this->assertEquals($t1->autocommit(), $initial_commit_state);
    }

    function test_nested_fail_1() {
        $t1 = new xTransaction();
        $initial_commit_state = $t1->autocommit();
        ## Transactions count = 0
        $this->assertEquals($t1::$started_transactions_count, 0);
        // Top level transaction (1)
        $t1->start();
        ## Autocommit is set to 0
        $this->assertEquals($t1->autocommit(), 0);
        ## Transactions count is increased (1)
        $this->assertEquals($t1::$started_transactions_count, 1);
        $r = $t1->execute_sql('SELECT * FROM unknown_table');
        ## Result is NOT a ressource
        $this->assertTrue(!is_resource($r));
        // Nested transaction (2)
        $t2 = new xTransaction();
        $t2->start();
        ## Autocommit stays at 0
        $this->assertEquals($t1->autocommit(), 0);
        ## Transactions count is increased (2)
        $this->assertEquals($t1::$started_transactions_count, 2);
        $r = $t2->execute_sql('SELECT * FROM personnes');
        ## Result is a ressource
        $this->assertTrue(is_resource($r));
        $r = $t2->execute_sql('SELECT * FROM personnes');
        ## Result is a ressource
        $this->assertTrue(is_resource($r));
        $t2->end();
        ## Autocommit stays at 0
        $this->assertEquals($t1->autocommit(), 0);
        ## Transactions count is decreased (1)
        $this->assertEquals($t1::$started_transactions_count, 1);
        try {
            $t1->end();
        } catch (Exception $e) {
            ## $t1 exception contains only its errors (1)
            $this->assertTrue($e instanceof xException);
            $this->assertEquals($e->getMessage(), '1 operation(s) failed during the transaction');
            $this->assertEquals(count($e->data['exceptions']), 1);
        }
        ## Transactions count is reset
        $this->assertEquals($t1::$started_transactions_count, 0);
        ## Autocommit is reset to initial autocommit state
        $this->assertEquals($t1->autocommit(), $initial_commit_state);
    }

    function test_nested_fail_2() {
        $t1 = new xTransaction();
        $initial_commit_state = $t1->autocommit();
        ## Transactions count = 0
        $this->assertEquals($t1::$started_transactions_count, 0);
        // Top level transaction (1)
        $t1->start();
        ## Autocommit is set to 0
        $this->assertEquals($t1->autocommit(), 0);
        ## Transactions count = 1
        $this->assertEquals($t1::$started_transactions_count, 1);
        $r = $t1->execute_sql('SELECT * FROM unknown_table');
        ## Result is NOT a ressource
        $this->assertTrue(!is_resource($r));
        // Nested transaction (2)
        $t2 = new xTransaction();
        $t2->start();
        ## Autocommit stays at 0
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
            $this->assertTrue($e instanceof xException);
            $this->assertEquals($e->getMessage(), '2 operation(s) failed during the transaction');
            $this->assertEquals(count($e->data['exceptions']), 2);
        }
        ## Transactions count is reset
        $this->assertEquals($t1::$started_transactions_count, 0);
        ## Autocommit is reset to initial autocommit state
        $this->assertEquals($t1->autocommit(), $initial_commit_state);
    }
}