<?php

/**
 * Tests xTransaction class.
 * Test are made at xModel level.
 */
class ArchivingTest extends iaPHPUnit_Framework_TestCase {

    function test_archive_model() {
        $archive = xModel::load('archive');
        $archive_data = xModel::load('archive_data');
        // Checks archive models are not-versioned
        $this->assertFalse($archive->versioning);
        $this->assertFalse($archive_data->versioning);
    }

    function test_commission() {
        $ids = array();
        // Creates a 'personne' as 'commission_membre' foreign model
        $r = xController::load('personnes', array(
            'id' => 0,
            'items' => array(
                'id' => 0,
                'nom' => 'Test archivage (nom)',
                'prenom' => 'Test archivage (prénom)'
            )
        ))->put();
        $ids['personne'] = $r['items']['id'];
        // Creates a commission and its related entities
        $commission_result = xController::load('commissions', array('items' => array(
            'nom' => 'Commission test pour archivage',
            'commission_type_id' => 1,
            'commission_etat_id' => 1,
            'section_id' => 2,
            'commentaire' => 'Un commentaire test'
        )))->put();
        $ids['commission'] = $commission_result['items']['id'];
        $r = xController::load('commissions_creations', array(
            'id' => $this->insertid($commission_result, 'commission_creation'),
            'items' => array(
                'id' => $this->insertid($commission_result, 'commission_creation'),
                'commission_id' => $ids['commission'],
                'commentaire' => 'Commentaire création',
                'date_decision' => '2010-12-30'
            )
        ))->post();
        $ids['commission_creation'] = $r['items']['id'];
        $r = xController::load('commissions_membres', array(
            'id' => 0,
            'items' => array(
                'id' => 0,
                'commission_id' => $ids['commission'],
                'personne_id' => $ids['personne'],
                'version_id' => null,
                'commission_fonction_id' => 2
            )
        ))->put();
        $ids['commission_membre'] = $r['items']['id'];
        $r = xController::load('commissions_candidats_commentaires', array(
            'id' => $this->insertid($commission_result, 'commission_candidat_commentaire'),
            'items' => array(
                'id' => $this->insertid($commission_result, 'commission_candidat_commentaire'),
                'commission_id' => $ids['commission'],
                'commentaire' => 'Commentaire commission candidats'
            )
        ))->post();
        $ids['commission_candidat_commentaire'] = $r['items']['id'];
        $r = xController::load('candidats', array(
            'id' => 0,
            'items' => array(
                'id' => 0,
                'commission_id' => $ids['commission'],
                'nom' => 'Test archivage (nom)',
                'prenom' => 'Test archivage (pr\u00e9nom)'
            )
        ))->put();
        $ids['candidat'] = $r['items']['id'];
        $r = xController::load('candidats_formations', array(
            'id' => 0,
            'items' => array(
                'id' => 0,
                'candidat_id' => $ids['candidat'],
                'formation_id' => 1,
                'lieu_these' => 'Quelque part'
            )
        ))->put();
        $ids['candidat_formation'] = $r['items']['id'];
        $ids['formation'] = $r['items']['formation_id'];
        // Archives commission
        xController::load('commissions', array(
            'id' => $ids['commission'],
            'items' => array(
                'id' => $ids['commission'],
                'commission_etat_id' => 3
            )
        ))->post();
        $ids['commission'] = $r['items']['id'];
        // Checks commission state
        $commission = xController::load('commissions', array(
            'id' => $ids['commission'],
        ))->get();
        $this->assertCount(1, $commission['items']);
        $commission = array_shift($commission['items']);
        $this->assertEquals(3, $commission['commission_etat_id']);
        // Checks commission write-lock
        // TODO //
        // Checks generated 'archive' row
        $archive = xModel::load('archive', array(
            'model_name' => 'commission',
            'id_field_value' => $ids['commission']
        ))->get();
        $this->assertCount(1, $archive);
        $archive = array_shift($archive);
        $archive_id =  $archive['id'];
        // Checks generated 'archive_data' rows
        // - Creates comparable structure for $data information
        $data_stored = array();
        foreach ($ids as $model => $id) {
            $r = xModel::load($model, array('id'=>$id))->get();
            $data_stored[$model][$id] = array_shift($r);
        }
        // - Creates comparable structure for 'archive_data' model
        $archive_data = xModel::load('archive_data', array(
            'archive_id' => $archive_id
        ))->get();
        $data_archive = array();
        foreach ($archive_data as $item) {
            $model = $item['model_name'];
            $id = $item['id_field_value'];
            $field = $item['model_field_name'];
            $value = $item['value'];
            $data_archive[$model][$id][$field] = $value;
        }
        // - Compares both 'stored' and 'archive' data
// ksort($data_archive);
// ksort($data_stored);
// $data_stored['dsa'] = 1;
// $diff = array_diff(
//     $data_archive,
//     $data_stored
// );
// var_dump($diff);
// return;
        // TODO: watch for model existing in $data_stored but not in $data_archive
        // TODO: does the thing below work?
        foreach($data_archive as $model => $records) {
            foreach($records as $id => $record) {
            var_dump("$model:$id");
                $diff = array_diff(
                    $data_archive[$model][$id],
                    $data_stored[$model][$id]
                );
                var_dump($diff);
            }
        }
    }
    protected function insertid($xresult, $modelname) {
        foreach ($xresult['xresults'] as $result) {
            if ($result['xmodel'] == $modelname) {
                $id = $result['result']['xinsertid'];
                break;
            }
        }
        if (!@$id) throw new xException('Count not find insert id');
        return $id;
    }
}