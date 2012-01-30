<?php

/**
 * Tests versioning feature through xModels (bypasses xControllers complexity)
 *
 * TODO:
 * - Test versioning robustness to errors, eg:
 *    - the controller starts a transaction, an error occurs within the transaction, no version should not be written (case: add personne_adresse)
 */
class VersioningTest extends iaPHPUnit_Framework_TestCase {

# For this test: easier reading
#
# - Create atomic actions helper methods, such as
#   - createEntity, createEntityFaulty, readEntity, updateEntity, deleteEntity, getEntity


# Tests to do:
#
# test_entity_create
# test_entity_modify
# test_entity_delete

# test_relation_n1_create
# test_relation_n1_modify
# test_relation_n1_delete
# *_fail
#
# test_relation_1n_create
# test_relation_1n_modify
# test_relation_1n_delete
# *_fail
#
# test_relation_nn_create
# test_relation_nn_modify
# test_relation_nn_delete
# *_fail
#
# + Test many modifications at once, then versions reliability
#

    /**
     * Returns the $n'th last version id
     */
    protected function get_last_version($n=0) {
        $r = xController::load('versions', array(
            'xorder_by' => 'id',
            'xorder' => 'DESC',
            'xlimit' => $n+1
        ), false)->get();
        return @$r['items'][$n]['id'];
    }

    /**
     * Asserts that the given $version_id contains the given $expected_changes.
     * @param integer The version id.
     * @param array The expected changes ('old value' => 'new value').
     */
    protected function assertVersionChanges($version_id, $expected_changes=array()) {
        $version_data = xModel::load('version_data', array(
            'version_id'=>$version_id
        ))->get();
        $actual_changes = array();
        foreach ($version_data as $data) {
            $field = $data['field_name'];
            $actual_changes[$field] = array(
                $data['old_value'] => $data['new_value']
            );
        }
        ksort($expected_changes);
        ksort($actual_changes);
        # All written version changes are expected
        $this->assertEquals($expected_changes, $actual_changes, print_r($expected_changes, true).print_r($actual_changes, true));
    }

    protected function create_personne($data=array()) {
        $personne = array_merge(
            array(
                'nom' => 'Nom',
                'prenom' => 'Prénom'
            ),
            $data
        );
        $r = xController::load('personnes', array('items'=>$personne))->put();
        return $r;
    }
    protected function modify_personne($data=array()) {
        $personne = array_merge(
            array(
                'id' => null,
                'nom' => 'Nom (modified)',
                'prenom' => 'Prénom (modified)'
            ),
            $data
        );
        $r = xController::load('personnes', array(
            'id' => $personne['id'],
            'items' => $personne
        ))->post();
        return $r;
    }
    protected function delete_personne($id) {
        return xController::load('personnes', array('id'=>$id))->delete();
    }

    function test_entity_create() {
        $r = $this->create_personne();
        $item = $r['items'];
        $id = $r['xinsertid'];
        # Record is correctly inserted
        $r = xController::load('personnes', array('id'=>$id))->get();
        $this->assertCount(1, $r['items']);
        $this->assertSame($item, @$r['items'][0]);
        # Version is correctly written
        $v = $this->get_last_version();
        $r = xModel::load('version', array('id'=>$v))->get(0);
        $this->assertEquals('personne', $r['model_name']);
        $this->assertEquals('personnes', $r['table_name']);
        $this->assertEquals('put', $r['operation']);
        $this->assertEquals('id', $r['id_field_name']);
        $this->assertEquals($id, $r['id_field_value']);
        # Version data is correctly written
        $changes = array(
            'id' => array(null => $id),
            'actif' => array(null => 1),
            'created' => array(null => $item['created']),
            'nom' => array(null => $item['nom']),
            'prenom' => array(null => $item['prenom'])
        );
        $this->assertVersionChanges($v, $changes);
        # Pre-insertion version is correctly inexistant
        $r = xController::load('personnes', array(
            'id' => $id,
            'xversion' => $this->get_last_version(1)
        ))->get();
        $this->assertCount(0, $r['items']);
        # Pre-insertion version xcount value is correct (0)
        // FIXME: xcount is not correct for now (BUG)
        //$this->assertEquals(0, $r['xcount']);
        // Returns created entity id
        return $id;
    }

    function test_entity_create_fail() {
        try {
            $r = $this->create_personne(array(
                'nom' => ''
            ));
            // TODO: fix xTransaction to ROLLBACK on any query exception (in execute_sql() & execute_model())
        } catch (Exception $e) {
            $this->assertTrue($e instanceof xException);
            $this->assertEquals($e->status, 400);
            $this->assertEquals($e->getMessage(), 'Invalid item data');
        }
    }

    /**
     * @depends test_entity_create
     */
    function test_entity_modify($id) {
        $r = xController::load('personnes', array('id'=>$id))->get();
        $item_old = $r['items'][0];
        $r = $this->modify_personne(array('id'=>$id));
        $item = $r['items'];
        # Record is correctly modified
        $r = xController::load('personnes', array('id'=>$id))->get();
        $this->assertCount(1, $r['items']);
        $this->assertSame($item, @$r['items'][0]);
        # Version is correctly written
        $v = $this->get_last_version();
        $r = xModel::load('version', array('id'=>$v))->get(0);
        $this->assertEquals('personne', $r['model_name']);
        $this->assertEquals('personnes', $r['table_name']);
        $this->assertEquals('post', $r['operation']);
        $this->assertEquals('id', $r['id_field_name']);
        $this->assertEquals($id, $r['id_field_value']);
        # Version data is correctly written
        $changes = array(
            'modified' => array($item_old['modified'] => $item['modified']),
            'nom' => array($item_old['nom'] => $item['nom']),
            'prenom' => array($item_old['prenom'] => $item['prenom'])
        );
        $this->assertVersionChanges($v, $changes);
        # Pre-modification version is correctly accessible
        $r = xController::load('personnes', array(
            'id' => $id,
            'xversion' => $this->get_last_version(1)
        ))->get();
        $this->assertCount(1, $r['items']);
        $this->assertEquals($item_old, $r['items'][0]);
        # Pre-modification version xcount value is correct (1)
        $this->assertEquals(1, $r['xcount']);
        // Returns modified entity id
        return $id;
    }

    /**
     * @depends test_entity_create
     */
    function test_entity_delete($id) {
        # Record correctly exists from tests depended upon
        $r = xController::load('personnes', array('id'=>$id))->get();
        $this->assertCount(1, $r['items']);
        $item_old = $r['items'][0];
        # Record is correctly deleted
        $r = $this->delete_personne($id);
        $r = xController::load('personnes', array('id'=>$id))->get();
        $this->assertCount(0, $r['items']);
        # xcount value is correct (0)
        // FIXME: xcount is not correct for now (BUG)
        //$this->assertEquals(0, $r['xcount']);
        # Version is correctly written
        $v = $this->get_last_version();
        $r = xModel::load('version', array('id'=>$v))->get(0);
        $this->assertEquals('personne', $r['model_name']);
        $this->assertEquals('personnes', $r['table_name']);
        $this->assertEquals('delete', $r['operation']);
        $this->assertEquals('id', $r['id_field_name']);
        $this->assertEquals($id, $r['id_field_value']);
        # Version data is correctly written
        // FIXME: shall all not-null fields be logged in version_data?
        //        better log only changed fields: 'modified' and 'actif'
        $changes = array(
            'id' => array($id => null),
            'actif' => array($item_old['actif'] => null),
            'created' => array($item_old['created'] => null),
            'modified' => array($item_old['modified'] => null),
            'nom' => array($item_old['nom'] => null),
            'prenom' => array($item_old['prenom'] => null)
        );
        $this->assertVersionChanges($v, $changes);
        # Pre-deletion version is correctly accessible
        $r = xController::load('personnes', array(
            'id' => $id,
            'xversion' => $this->get_last_version(1)
        ))->get();
        $this->assertCount(1, $r['items']);
        $this->assertEquals($item_old, $r['items'][0]);
        # Pre-deletion version xcount value is correct (1)
        $this->assertEquals(1, $r['xcount']);
        // Returns modified entity id
        return $id;
    }

    function test_relation_n1_create() {
        // Test personne---pays here:
        // ==========================
        // - create a personne
        // - modify personne.pays_id
        // - test personne pre-modification version
        // - modify pays_id
        // - test pays pre-modification version through personne
        //
        // TODO first: version catalogs
    }

    /**
     */
    function test_relation_1n_create() {
        $personne_id = $this->test_entity_create();
        // Creates a foreign record
        $r = xController::load('personnes_emails', array('items'=>array(
            'personne_id' => $personne_id,
            'adresse_type_id' => 1,
            'email' => 'name@example.com'
        )))->put();
        $id = $r['items']['id'];
        $item = $r['items'];
        # Record is correctly inserted
        $r = xController::load('personnes_emails', array('id'=>$id))->get();
        $this->assertCount(1, $r['items']);
        $this->assertSame($item, @$r['items'][0]);
        # Version is correctly written
        $v = $this->get_last_version();
        $r = xModel::load('version', array('id'=>$v))->get(0);
        $this->assertEquals('personne_email', $r['model_name']);
        $this->assertEquals('personnes_emails', $r['table_name']);
        $this->assertEquals('put', $r['operation']);
        $this->assertEquals('id', $r['id_field_name']);
        $this->assertEquals($id, $r['id_field_value']);
        # Version data is correctly written
        $changes = array(
            'id' => array(null => $id),
            'actif' => array(null => 1),
            'created' => array(null => $item['created']),
            'personne_id' => array(null => $item['personne_id']),
            'adresse_type_id' => array(null => $item['adresse_type_id']),
            'email' => array(null => $item['email']),
            'defaut' => array(null => $item['defaut'])
        );
        $this->assertVersionChanges($v, $changes);
        # Pre-insertion version is correctly inexistant
        $r = xController::load('personnes_emails', array(
            'id' => $id,
            'xversion' => $this->get_last_version(1)
        ))->get();
        $this->assertCount(0, $r['items']);
        # Pre-insertion version xcount value is correct (0)
        // FIXME: xcount is not correct for now (BUG)
        //$this->assertEquals(0, $r['xcount']);
        // Returns created entity id
        return $id;
    }

    /**
     * @depends test_relation_1n_create
     */
    function test_relation_1n_modify($id) {
        $r = xController::load('personnes_emails', array('id'=>$id))->get();
        $item_old = $r['items'][0];
        // Modifies the foreign record
        $r = xController::load('personnes_emails', array('items'=>array(
            'id' => $id,
            'adresse_type_id' => 2,
            'email' => 'modified_name@example.com'
        )))->post();
        $id = $r['items']['id'];
        $item = $r['items'];
        # Record is correctly inserted
        $r = xController::load('personnes_emails', array('id'=>$id))->get();
        $this->assertSame($item, @$r['items'][0]);
        # Version is correctly written
        $v = $this->get_last_version();
        $r = xModel::load('version', array('id'=>$v))->get(0);
        $this->assertEquals('personne_email', $r['model_name']);
        $this->assertEquals('personnes_emails', $r['table_name']);
        $this->assertEquals('post', $r['operation']);
        $this->assertEquals('id', $r['id_field_name']);
        $this->assertEquals($id, $r['id_field_value']);
        # Version data is correctly written
        $changes = array(
            'modified' => array($item_old['modified'] => $item['modified']),
            'adresse_type_id' => array($item_old['adresse_type_id'] => $item['adresse_type_id']),
            'email' => array($item_old['email'] => $item['email'])
        );
        $this->assertVersionChanges($v, $changes);
        # Pre-modification version is correctly accessible
        $r = xController::load('personnes_emails', array(
            'id' => $id,
            'xversion' => $this->get_last_version(1)
        ))->get();
        $this->assertCount(1, $r['items']);
        $this->assertEquals($item_old, $r['items'][0]);
        # Pre-modification version xcount value is correct (1)
        $this->assertEquals(1, $r['xcount']);
        // Returns modified entity id
        return $id;

    }

    /**
     * FIXME: as of the actual mecanisms, personne can never be deleted even
     * Tests entity deletion when foreign key constraint fails.
     * @depends test_relation_1n_modify
     */
    function test_relation_1n_delete($id) {
        # Record correctly exists from tests depended upon
        $r = xController::load('personnes_emails', array('id'=>$id))->get();
        $item_old = $r['items'][0];
        $this->assertCount(1, $r['items']);
        # Record is correctly deleted
        $r = xController::load('personnes_emails', array('id'=>$id))->delete();
        $r = xController::load('personnes_emails', array('id'=>$id))->get();
        $this->assertCount(0, $r['items']);
        # xcount value is correct (0)
        // FIXME: xcount is not correct for now (BUG)
        //$this->assertEquals(0, $r['xcount']);
        # Version is correctly written
        $v = $this->get_last_version();
        $r = xModel::load('version', array('id'=>$v))->get(0);
        $this->assertEquals('personne_email', $r['model_name']);
        $this->assertEquals('personnes_emails', $r['table_name']);
        $this->assertEquals('delete', $r['operation']);
        $this->assertEquals('id', $r['id_field_name']);
        $this->assertEquals($id, $r['id_field_value']);
        # Version data is correctly written
        // FIXME: shall all not-null fields be logged in version_data?
        //        better log only changed fields: 'modified' and 'actif'
        $changes = array(
            'id' => array($id => null),
            'actif' => array($item_old['actif'] => null),
            'created' => array($item_old['created'] => null),
            'modified' => array($item_old['modified'] => null),
            'personne_id' => array($item_old['personne_id'] => null),
            'adresse_type_id' => array($item_old['adresse_type_id'] => null),
            'email' => array($item_old['email'] => null),
            'defaut' => array($item_old['defaut'] => null)
        );
        $this->assertVersionChanges($v, $changes);
        # Pre-deletion version is correctly accessible
        $r = xController::load('personnes_emails', array(
            'id' => $id,
            'xversion' => $this->get_last_version(1)
        ))->get();
        $this->assertEquals($item_old, $r['items'][0]);
        # Pre-deletion version xcount value is correct (1)
        $this->assertEquals(1, $r['xcount']);
        // Returns modified entity id
        return $id;
    }


// OLD AND MESSY //////////////////////////////////////////////////////////////

    function _test_entity() {
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
    function _test_1n_relations_modifications() {
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
}catch(Exception $e){ var_dump($e->data['exceptions']);die(); }
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
}