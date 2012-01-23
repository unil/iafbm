<?php

/**
 * Tests versioning feature through xModels (bypasses xControllers complexity)
 *
 * TODO:
 * - Test versioning robustness to errors, eg:
 *    - the controller starts a transaction, an error occurs within the transaction, no version should not be written (case: add personne_adresse)
 */
class VersioningTest extends iaPHPUnit_Framework_TestCase
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
    function get_last_version() {
        $r = xController::load('versions', array(
            'xorder_by' => 'id',
            'xorder' => 'DESC',
            'xlimit' => 1
        ), false)->get();
        return $r['items'][0]['id'];
    }
    function dump() {
        foreach(func_get_args() as $arg) var_dump($arg);
    }

    function _testEntity() {
        // Creates a new personne
        $personne_v1 = array(
            'nom' => 'Nom',
            'prenom' => 'Prénom'
        );
        $r = $this->create('personnes', $personne_v1)->put();
        $personne_v1 = $r['items'];
        $personne_v1_id = $r['insertid'];
        // Modifies personne
        $personne_v2 = array_merge($personne_v1, array(
            'id' => $personne_v1_id,
            'nom' => 'Nouveau'
        ));
        $r = $this->create('personnes', $personne_v2)->post();
        $personne_v2 = $r['items'];
        // Gets latest version
        $last_version = $this->get_last_version();
        // Fetches personne versions
        $r = xController::load('personnes', array(
            'id'=>$personne_v1_id,
            'xversion' => $last_version-1
        ), false)->get();
        $personne_v1_get = $r['items'][0];
        $r = xController::load('personnes', array(
            'id' => $personne_v1_id
        ), false)->get();
        $personne_v2_get = $r['items'][0];
        // Asserts versions
        $this->assertEquals($personne_v1, $personne_v1_get);
        $this->assertEquals($personne_v2, $personne_v2_get);
    }

    // Personne relations (adresse)
    function test_1n_relations_modifications() {
        // Creates a new personne
        $personne = array(
            'nom' => 'Nom',
            'prenom' => 'Prénom'
        );
        $r = $this->create('personnes', $personne)->put();
        $personne = $r['items'];
        $personne_id = $r['insertid'];
        // Creates an adresse
        $adresse_1 = array(
            'personne_id' => $personne_id,
            'adresse_rue' => 'Rue 123',
            'adresse_npa' => '1000',
            'adresse_lieu' => 'Lieu',
            'adresse_pays_id' => 1,
            'adresse_adresse_type_id' => 1
        );
        $r = $this->create('personnes_adresses', $adresse_1)->put();
        $adresse_1 = $r['items'];
        $adresse_1_id = $r['items']['adresse_id'];
        $personne_adresse_1_id = $r['items']['id'];
        // Creates another adresse
        $adresse_2 = array(
            'personne_id' => $personne_id,
            'adresse_rue' => 'Rue 999',
            'adresse_npa' => '9999',
            'adresse_lieu' => 'Lieu autre',
            'adresse_pays_id' => 2,
            'adresse_adresse_type_id' => 1
        );
        $r = $this->create('personnes_adresses', $adresse_2)->put();
        $adresse_2 = $r['items'];
        $adresse_2_id = $r['items']['adresse_id'];
        $personne_adresse_2_id = $r['items']['id'];
        // Checks data insertion
        $r = $this->get('personnes_adresses', array(
            'personne_id' => $personne_id,
            'order_by' => 'id',
            'order' => 'ASC'
        ));
        $this->assertEquals(
            $r['items'],
            array($adresse_1, $adresse_2)
        );
        // Modifies adresse 1
        $r = $this->create('personnes_adresses', array(
            'id' => $personne_adresse_1_id,
            'adresse_id' => $adresse_1_id,
            'adresse_rue' => 'Rue 321 (modifiée)'
        ))->post();
        $adresse_1_v2 = $r['items'];
        // Fetches personne_adresse versions
        $last_version = $this->get_last_version();
        $r = $this->get('personnes_adresses', array(
            'id' => $personne_adresse_1_id,
            'xversion' => $last_version-2
        ));
        $adresse_1_get = $r['items'][0];
        $r = $this->get('personnes_adresses', array(
            'id' => $personne_adresse_2_id
        ));
        $adresse_2_get = $r['items'][0];
        $r = $this->get('personnes_adresses', array(
            'id' => $personne_adresse_1_id,
            //'xversion' => $last_version
        ));
        $adresse_1_v2_get = $r['items'][0];
        // Asserts foreign models versions
        //$this->dump($last_version, $adresse_1, $adresse_1_get);
        $this->assertEquals($adresse_1, $adresse_1_get);
        $this->assertEquals($adresse_1_v2, $adresse_1_v2_get);
        $this->assertEquals($adresse_2, $adresse_2_get);
        // Deletes foreign entity
try{
        xController::load('personnes_adresses', array(
            'id' => $personne_adresse_1_id
        ))->delete();
}catch(Exception $e){ var_dump($e);die(); }
return;
        $last_version = $this->get_last_version();
        $r = $this->get('personnes_adresses', array(
            'personne_id' => $personne_id,
            'xversion' => $last_version-1
        ));
        $adresses = $r['items'];
var_dump($r);
        //$this->asserEquals();

    }
    // Commission_membre
    function _test_nn_relations() {
    }

    function _testPushAndPop()
    {
        $stack = array();
        $this->assertEquals(0, count($stack));

        array_push($stack, 'foo');
        $this->assertEquals('foo', $stack[count($stack)-1]);
        $this->assertEquals(1, count($stack));

        $this->assertEquals('foo', array_pop($stack));
        $this->assertEquals(0, count($stack));
    }
}