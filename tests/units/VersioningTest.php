<?php

/**
 * Tests versioning feature through xModels (bypasses xControllers complexity)
 * @package unittests
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
     * @param int The n'th last version whose id is to be returned.
     * @return int the $n'th last version id
     */
    protected function get_last_version($n=0) {
        $r = xController::load('versions', array(
            'xorder_by' => 'id',
            'xorder' => 'DESC',
            'xlimit' => $n+1
        ), false)->get();
        $v = @$r['items'][$n]['id'];
        $this->assertNotEmpty($v);
        return $v;
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
                'nom' => "Nom",
                'prenom' => "Prénom"
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
            $r = $this->create_personne(array('nom' => ''));
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
        # Record correctly exists from tests depended upon
        $r = xController::load('personnes', array('id'=>$id))->get();
        $this->assertCount(1, $r['items']);
        $item_old = $r['items'][0];
        # Record is correctly modified
        $r = $this->modify_personne(array('id'=>$id));
        $item = $r['items'];
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
     * @expectedException xException
     * @expectedExceptionMessage The requested personne
     */
    function test_entity_delete($id) {
        # Record correctly exists from tests depended upon
        $r = xController::load('personnes', array('id'=>$id))->get();
        $this->assertCount(1, $r['items']);
        $item_old = $r['items'][0];
        # Record is correctly deleted
        sleep(1); // Sleeps to make sure 'modified' differs from record last creation/modification
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
            'actif' => array($item_old['actif'] => 0),
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

    function test_relation_11_create() {
        // Retrieves an existing 'Pays'
        $r = xController::load('pays', array('id'=>15))->get();
        # Pays exists
        $this->assertCount(1, $r['items']);
        $pays = $r['items'][0];
        # Personne correctly created
        $r = $this->create_personne();
        $item_old = $r['items'];
        $id = $r['xinsertid'];
        $this->assertEquals(null, $item_old['pays_id']);
        # Personne is correctly modified
        $r = $this->modify_personne(array(
            'id' => $id,
            'pays_id' => $pays['id']
        ));
        $item = $r['items'];
        $r = xController::load('personnes', array('id'=>$id))->get();
        $this->assertCount(1, $r['items']);
        $this->assertEquals($item, $r['items'][0]);
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
            'nom' => array($item_old['nom'] => $item['nom']),
            'prenom' => array($item_old['prenom'] => $item['prenom']),
            'pays_id' => array($item_old['pays_id'] => $item['pays_id']),
        );
        $this->assertVersionChanges($v, $changes);
        # 'Pays' is correctly retrieved through 'Personne'
        $r = xController::load('personnes', array('id'=>$id))->get();
        $this->assertCount(1, $r['items']);
        $item = $r['items'][0];
        $this->assertEquals($item['pays_id'], $pays['id']);
        $this->assertEquals($item['pays_code'], $pays['code']);
        $this->assertEquals($item['pays_nom'], $pays['nom']);
        # xcount value is correct (1)
        $this->assertEquals(1, $r['xcount']);
        # Pre-modification version is correctly accessible through 'Personne'
        $r = xController::load('personnes', array(
            'id' => $id,
            'xversion' => $this->get_last_version(1)
        ))->get();
        $this->assertCount(1, $r['items']);
        $this->assertEquals($item_old, $r['items'][0]);
        # Pre-modification version xcount value is correct (1)
        $this->assertEquals(1, $r['xcount']);
        return $id;
    }

    /**
     * Tests versioning on related (Pays) entity
     * @depends test_relation_11_create
     */
    function test_relation_11_modify_1($id) {
        # 'Personne' record correctly exists from tests depended upon
        $r = xController::load('personnes', array('id'=>$id))->get();
        $this->assertCount(1, $r['items']);
        $item_old = $r['items'][0];
        # 'Pays' record correctly exists from tests depended upon
        $r = xController::load('pays', array('id'=>$item_old['pays_id']))->get();
        $this->assertCount(1, $r['items']);
        $pays_old = $r['items'][0];
        # 'Pays' record is correctly modified
        $r = xController::load('pays', array(
            'id' => $pays_old['id'],
            'items' => array(
                'id' => $pays_old['id'],
                'nom' => 'Aruba_modified_'.microtime()
            )
        ))->post();
        $pays = $r['items'];
        $r = xController::load('pays', array('id'=>$pays_old['id']))->get();
        $this->assertCount(1, $r['items']);
        $this->assertEquals($pays, $r['items'][0]);
        # Version is correctly written
        $v = $this->get_last_version();
        $r = xModel::load('version', array('id'=>$v))->get(0);
        $this->assertEquals('pays', $r['model_name']);
        $this->assertEquals('pays', $r['table_name']);
        $this->assertEquals('post', $r['operation']);
        $this->assertEquals('id', $r['id_field_name']);
        $this->assertEquals($pays['id'], $r['id_field_value']);
        # Version data is correctly written
        $changes = array(
            'nom' => array($pays_old['nom'] => $pays['nom'])
        );
        $this->assertVersionChanges($v, $changes);
        # Record modification is correctly accessible through 'Personne'
        $r = xController::load('personnes', array('id'=>$id))->get();
        $this->assertCount(1, $r['items']);
        $item = $r['items'][0];
        $this->assertEquals($pays['id'], $item['pays_id']);
        $this->assertEquals($pays['nom'], $item['pays_nom']);
        # xcount value is correct (1)
        $this->assertEquals(1, $r['xcount']);
        # Pre-modification version is correctly accessible through 'Personne'
        $r = xController::load('personnes', array(
            'id' => $id,
            'xversion' => $this->get_last_version(1)
        ))->get();
        $this->assertCount(1, $r['items']);
        $this->assertEquals($item_old, $r['items'][0]);
        # Pre-modification version xcount value is correct (1)
        $this->assertEquals(1, $r['xcount']);
        return $id;
    }

    /**
     * Tests versioning on relating (Personne) entity
     * @depends test_relation_11_create
     */
    function test_relation_11_modify_2($id) {
        # 'Personne' record correctly exists from tests depended upon
        $r = xController::load('personnes', array('id'=>$id))->get();
        $this->assertCount(1, $r['items']);
        $item_old = $r['items'][0];
        # 'Personne' record is correctly modified
        $r = xController::load('personnes', array(
            'id' => $id,
            'items' => array_merge(
                $item_old,
                array(
                    'id' => $id,
                    'pays_id' => 1
                )
            )
        ))->post();
        $item = $r['items'];
        $r = xController::load('personnes', array('id'=>$id))->get();
        $this->assertCount(1, $r['items']);
        $this->assertEquals($item, $r['items'][0]);
        # Version is correctly written
        $v = $this->get_last_version();
        $r = xModel::load('version', array('id'=>$v))->get(0);
        $this->assertEquals('personne', $r['model_name']);
        $this->assertEquals('personnes', $r['table_name']);
        $this->assertEquals('post', $r['operation']);
        $this->assertEquals('id', $r['id_field_name']);
        $this->assertEquals($item['id'], $r['id_field_value']);
        # Version data is correctly written
        $changes = array(
            'pays_id' => array($item_old['pays_id'] => $item['pays_id'])
        );
        $this->assertVersionChanges($v, $changes);
        # Record is modification is correctly accessible through 'Personne'
        $r = xController::load('personnes', array('id'=>$id))->get();
        $this->assertCount(1, $r['items']);
        $item = $r['items'][0];
        $this->assertEquals($item['pays_id'], 1);
        $this->assertEquals($item['pays_nom'], 'Afghanistan');
        # xcount value is correct (1)
        $this->assertEquals(1, $r['xcount']);
        # Pre-modification version is correctly accessible through 'Personne'
        $r = xController::load('personnes', array(
            'id' => $id,
            'xversion' => $this->get_last_version(1)
        ))->get();
        $this->assertCount(1, $r['items']);
        $this->assertEquals($item_old, $r['items'][0]);
        # Pre-modification version xcount value is correct (1)
        $this->assertEquals(1, $r['xcount']);
        return $id;
    }

    /**
     * Tests entity deletion when foreign key constraint fails.
     * As of the actual mecanisms, personne can never be deleted
     * even when tough all foreign model are soft-deleted (actif=0).
     * @depends test_relation_11_create
     */
    function test_relation_11_delete($id) {
        # 'Personne' record correctly exists from tests depended upon
        $r = xController::load('personnes', array('id'=>$id))->get();
        $this->assertCount(1, $r['items']);
        $item = $r['items'][0];
        # Pays removal correctly throws an exception
        try {
            $r = xController::load('pays', array('id'=>$item['pays_id']))->delete();
        } catch (Exception $e) {
            $this->assertTrue($e instanceof xException);
            $this->assertEquals($e->status, 500);
            $this->assertRegExp('/^Invalid query: DELETE FROM/', $e->getMessage());
            $this->assertRegExp('/Cannot delete or update a parent row: a foreign key constraint fails/', $e->getMessage());
        }
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
        # Record correctly exists from tests depended upon
        $r = xController::load('personnes_emails', array('id'=>$id))->get();
        $this->assertCount(1, $r['items']);
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
     * Tests entity deletion when foreign key constraint passes.
     * @depends test_relation_1n_create
     * @expectedException xException
     * @expectedExceptionMessage The requested personne_email
     */
    function test_relation_1n_delete($id) {
        # Record correctly exists from tests depended upon
        $r = xController::load('personnes_emails', array('id'=>$id))->get();
        $this->assertCount(1, $r['items']);
        $item_old = $r['items'][0];
        # Record is correctly deleted
        sleep(1); // Sleeps to make sure 'modified' differs from record last creation/modification
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
            'actif' => array($item_old['actif'] => 0),
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

    function test_relation_nn_create_personne() {
        $personne_id = $this->test_entity_create();
        return $personne_id;
    }

    /**
     * @depends test_relation_nn_create_personne
     */
    function test_relation_nn_create_adresse($personne_id) {
        $r = xController::load('personnes_adresses', array('items'=>array(
            'personne_id' => $personne_id,
            'adresse_adresse_type_id' => 1,
            'adresse_rue' => 'Une Rue 123',
            'adresse_npa' => '999',
            'adresse_lieu' => 'Clouds',
            'adresse_pays_id' => 2
        )))->put();
        $id = $r['items']['id'];
        $adresse_id = $r['items']['adresse_id'];
        $item = $r['items'];
        # Record is correctly inserted
        $r = xController::load('personnes_adresses', array('id'=>$id))->get();
        $this->assertCount(1, $r['items']);
        $this->assertSame($item, $r['items'][0]);
        # 'Adresse' version is correctly written
        $v = $this->get_last_version(1);
        $r = xModel::load('version', array('id'=>$v))->get(0);
        $this->assertEquals('adresse', $r['model_name']);
        $this->assertEquals('adresses', $r['table_name']);
        $this->assertEquals('put', $r['operation']);
        $this->assertEquals('id', $r['id_field_name']);
        $this->assertEquals($adresse_id, $r['id_field_value']);
        # 'Adresse' version data is correctly written
        $changes = array(
            'id' => array(null => $adresse_id),
            'actif' => array(null => 1),
            'adresse_type_id' => array(null => $item['adresse_adresse_type_id']),
            'rue' => array(null => $item['adresse_rue']),
            'npa' => array(null => $item['adresse_npa']),
            'lieu' => array(null => $item['adresse_lieu']),
            'pays_id' => array(null => $item['adresse_pays_id'])
        );
        $this->assertVersionChanges($v, $changes);
        # 'PersonneAdresse' version is correctly written
        $v = $this->get_last_version(0);
        $r = xModel::load('version', array('id'=>$v))->get(0);
        $this->assertEquals('personne_adresse', $r['model_name']);
        $this->assertEquals('personnes_adresses', $r['table_name']);
        $this->assertEquals('put', $r['operation']);
        $this->assertEquals('id', $r['id_field_name']);
        $this->assertEquals($id, $r['id_field_value']);
        # Version data is correctly written
        $changes = array(
            'id' => array(null => $id),
            'actif' => array(null => 1),
            'personne_id' => array(null => $item['personne_id']),
            'adresse_id' => array(null => $item['adresse_id']),
            'defaut' => array(null => $item['defaut'])
        );
        $this->assertVersionChanges($v, $changes);
        # Pre-insertion version is correctly inexistant
        $r = xController::load('personnes_adresses', array(
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
     * @depends test_relation_nn_create_adresse
     */
    function test_relation_nn_modify($id) {
        # Record correctly exists from tests depended upon
        $r = xController::load('personnes_adresses', array(
            'id' => $id,
            'xjoin' => 'personne,adresse'
        ))->get();
        $this->assertCount(1, $r['items']);
        $item_old = $r['items'][0];
        # 'Adresse' record is correctly modified
        $r = xController::load('personnes_adresses', array(
            'id' => $id,
            'items' => array(
                'id' => $id,
                'personne_id' => 1
            )
        ))->post();
        $item = $r['items'];
        $personne_id = $item_old['personne_id'];
        $r = xController::load('personnes_adresses', array('id'=>$id))->get();
        $this->assertCount(1, $r['items']);
        $this->assertSame($item, $r['items'][0]);
        # Pre-modification version is correctly accessible
        $r = xController::load('personnes_adresses', array(
            'id' => $id,
            'xjoin' => 'personne,adresse',
            'xversion' => $this->get_last_version(1)
        ))->get();
        $this->assertEquals($item_old, $r['items'][0]);
        # 'Personne' record is correctly modified
        $r = xController::load('personnes_adresses', array(
            'id' => $id,
            'items' => array(
                'id' => $id,
                'personne_id' => 1
            )
        ))->post();
        $item = $r['items'];
        $personne_id = $r['items']['personne_id'];
        $r = xController::load('personnes_adresses', array('id'=>$id))->get();
        $this->assertCount(1, $r['items']);
        $this->assertSame($item, $r['items'][0]);
        # Pre-modification version is correctly accessible
        $r = xController::load('personnes_adresses', array(
            'id' => $id,
            'xjoin' => 'personne,adresse',
            'xversion' => $this->get_last_version(1) //was (1)
        ))->get();
        $this->assertCount(1, $r['items']);
        $this->assertEquals($item_old, $r['items'][0]);
        # Pre-modification version xcount value is correct (1)
        $this->assertEquals(1, $r['xcount']);
        // Returns modified entity id
        return $id;
    }

    /**
    * @depends test_relation_nn_modify
    */
    function test_relation_nn_delete($id) {
        # Record correctly exists from tests depended upon
        $r = xController::load('personnes_adresses', array(
            'id' => $id,
            'xjoin' => 'personne,adresse'
        ))->get();
        $this->assertCount(1, $r['items']);
        $item_old = $r['items'][0];
        $personne_id = $item_old['personne_id'];
        $adresse_id = $item_old['adresse_id'];
        # 'Peronne' deletion correctly fails
        try {
            $r = xController::load('personnes', array('id'=>$personne_id))->delete();
        } catch (Exception $e) {
            $this->assertTrue($e instanceof xException);
            $this->assertEquals($e->status, 500);
            $this->assertRegExp('/^Invalid query: DELETE FROM/', $e->getMessage());
            $this->assertRegExp('/Cannot delete or update a parent row: a foreign key constraint fails/', $e->getMessage());
        }
        # 'Adresse' deletion correctly fails
        try {
            $r = xController::load('adresses', array('id' => $id))->delete();
        } catch (Exception $e) {
            $this->assertTrue($e instanceof xException);
            $this->assertEquals($e->status, 500);
            $this->assertRegExp('/^Invalid query: DELETE FROM/', $e->getMessage());
            $this->assertRegExp('/Cannot delete or update a parent row: a foreign key constraint fails/', $e->getMessage());
        }
    }

    function test_relations_nn_create_more_adresses() {
        $personne_id = $this->test_relation_nn_create_personne();
        $ids = array();
        // Adds multiple Adresses for a Personne
        foreach (range(1,10) as $i) {
            $id = $this->test_relation_nn_create_adresse($personne_id);
            $ids[] = $id;
            $v = $this->get_last_version();
            // For each version, store the result list
            $r = xController::load('personnes_adresses', array(
                'personne_id' => $personne_id
            ))->get();
            $items_by_personne_id[$v] = $r['items'];
            // For each version, store the result item
            $r = xController::load('personnes_adresses', array(
                'id' => $id
            ))->get();
            $items_by_id[$v] = $r['items'];
        }
        # Versions are correctly accessible by personne_id
        foreach ($items_by_personne_id as $v => $items) {
            $r = xController::load('personnes_adresses', array(
                'personne_id' => $personne_id,
                'xversion' => $v
            ))->get();
            $this->assertEquals($items, $r['items']);
        }
        # Previous version of each list correctly contains 1 item less
        foreach ($items_by_personne_id as $v => $items) {
            $r = xController::load('personnes_adresses', array(
                'personne_id' => $personne_id,
                'xversion' => $v-1
            ))->get();
            $this->assertCount(count($items)-1, $r['items']);
        }
        # Versions are correctly accessible by id
        foreach ($items_by_id as $v => $items) {
            $r = xController::load('personnes_adresses', array(
                'id' => $items[0]['id'],
                'xversion' => $v
            ))->get();
            $this->assertEquals($items, $r['items']);
        }
        # Previous version of each record does correctly NOT exist
        foreach ($items_by_id as $v => $items) {
            $r = xController::load('personnes_adresses', array(
                'id' => $items[0]['id'],
                'xversion' => $v-1
            ))->get();
            $this->assertEquals(array(), $r['items']);
        }
        return $personne_id;
    }

    /**
    * @depends test_relations_nn_create_more_adresses
    */
    function _test_relations_nn_modify_more_adresses($personne_id) {
    }

    /**
    * @depends test_relations_nn_create_more_adresses
    */
    function test_relations_nn_delete_more_adresses($personne_id) {
        # Records correctly exist from tests depended upon
        $r = xController::load('personnes_adresses', array(
            'personne_id' => $personne_id
        ))->get();
        $this->assertNotEmpty($r['items']);
        $this->assertCount(10, $r['items']);
        $items = $r['items'];
        // Deletes Addresses one-by-one for the given Personne
        foreach ($items as $item) {
            // For each version, store the result item
            $v = $this->get_last_version();
            $r = xController::load('personnes_adresses', array(
                'id' => $item['id']
            ))->get();
            $items_by_id[$v] = $r['items'];
            // Actual item deletion
            $r = xController::load('personnes_adresses', array(
                'id' => $item['id']
            ))->delete();
            // For each version, store the result list
            $v = $this->get_last_version();
            $r = xController::load('personnes_adresses', array(
                'personne_id' => $personne_id
            ))->get();
            $items_by_personne_id[$v] = $r['items'];
        }
        # Versions are correctly accessible by personne_id
        foreach ($items_by_personne_id as $v => $items) {
            $r = xController::load('personnes_adresses', array(
                'personne_id' => $personne_id,
                'xversion' => $v
            ))->get();
            $this->assertEquals(count($items), count($r['items']));
            $this->assertEquals($items, $r['items']);
        }
        # Previous version of each list correctly contains 1 item more
        foreach ($items_by_personne_id as $v => $items) {
            $r = xController::load('personnes_adresses', array(
                'personne_id' => $personne_id,
                'xversion' => $v-2
            ))->get();
            $this->assertCount(count($items)+1, $r['items']);
        }
        # Versions are correctly accessible by id
        foreach ($items_by_id as $v => $items) {
            $r = xController::load('personnes_adresses', array(
                'id' => $items[0]['id'],
                'xversion' => $v
            ))->get();
            $this->assertEquals($items, $r['items']);
        }
        # Post-delete version of each record does correctly NOT exist
        foreach ($items_by_id as $v => $items) {
            $r = xController::load('personnes_adresses', array(
                'id' => $items[0]['id'],
                'xversion' => $v+2
            ))->get();
            $this->assertEquals(array(), $r['items']);
        }
        return $personne_id;
    }
}