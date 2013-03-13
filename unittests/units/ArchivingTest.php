<?php

/**
 * Tests xTransaction class.
 * Test are made at xModel level.
 * @package unittests
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
        $ids['commission_type'] = $commission_result['items']['commission_type_id'];
        $ids['commission_etat'] = $commission_result['items']['commission_etat_id'];
        $ids['section'] = $commission_result['items']['section_id'];
        // - Updates commission 'creation'
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
        // - Creates commission 'membres'
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
        $ids['commission_fonction'] = $r['items']['commission_fonction_id'];
        // - Updates commission 'candidats commentaire'
        $r = xController::load('commissions_candidats_commentaires', array(
            'id' => $this->insertid($commission_result, 'commission_candidat_commentaire'),
            'items' => array(
                'id' => $this->insertid($commission_result, 'commission_candidat_commentaire'),
                'commission_id' => $ids['commission'],
                'commentaire' => 'Commentaire commission candidats'
            )
        ))->post();
        $ids['commission_candidat_commentaire'] = $r['items']['id'];
        // - Creates commission 'candidats'
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
        // - Creates commission 'candidats' 'formations'
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
        // - Updates commission 'travail'
        $r = xController::load('commissions_travails', array(
            'id' => $this->insertid($commission_result, 'commission_travail'),
            'items' => array(
                'id' => $this->insertid($commission_result, 'commission_travail'),
                'commission_id' => $ids['commission'],
                'primo_loco' => $ids['candidat'],
                'commentaire' => 'Commentaire commission travail'
            )
        ))->post();
        $ids['commission_travail'] = $r['items']['id'];
        // - Creates commission 'candidats'
        $r = xController::load('commissions_travails_evenements', array(
            'id' => 0,
            'items' => array(
                'id' => 0,
                'commission_id' => $ids['commission'],
                'commission_travail_evenement_type_id' => 2,
                'date' => '2000-01-31'
            )
        ))->put();
        $ids['commission_travail_evenement'] = $r['items']['id'];
        $ids['commission_travail_evenement_type'] = $r['items']['commission_travail_evenement_type_id'];
        // - Leave commission 'validation' unchanged
        $ids['commission_validation'] = $this->insertid($commission_result, 'commission_validation');
        // - Leave commission 'finalisation' unchanged
        $ids['commission_finalisation'] = $this->insertid($commission_result, 'commission_finalisation');
        // Archives commission (by 'closing' it)
        $r = xController::load('commissions', array(
            'id' => $ids['commission'],
            'items' => array(
                'id' => $ids['commission'],
                'commission_etat_id' => 3
            )
        ))->post();
        $ids['commission'] = $r['items']['id'];
        $ids['commission_etat'] = $r['items']['commission_etat_id'];
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
        // Checks generated archive
        // - Creates comparable structure for $data information
        $data_stored = array();
        foreach ($ids as $model => $id) {
            $r = xModel::load($model, array(
                'id' => $id,
                'xjoin' => ''
            ))->get();
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
        // - Checks that both data contain the same models
        $diff_models = $this->diff(
            array_keys($data_stored),
            array_keys($data_archive)
        );
        $this->assertEmpty($diff_models);
        $models = array_keys($data_archive);
        foreach($models as $model) {
            // - Checks that both data contain the same records ids
            $diff_ids = $this->diff(
                $data_stored[$model],
                $data_archive[$model]
            );
            $this->assertEmpty($diff_ids);
            $model_ids = array_unique(array_merge(
                array_keys($data_stored[$model]),
                array_keys($data_archive[$model])
            ));
            // - Checks that both data contain the same records fields
            foreach($model_ids as $id) {
                $diff_fields = $this->diff(
                    array_keys($data_stored[$model][$id]),
                    array_keys($data_archive[$model][$id])
                );
                $this->assertEmpty($diff_fields);
                // - Checks that both data contain the same records data
                $fields = array_keys($data_archive[$model][$id]);
                foreach($fields as $field) {
                    $value_stored = $data_stored[$model][$id][$field];
                    $value_archive = $data_archive[$model][$id][$field];
                    $this->assertSame($value_stored, $value_archive, "$model:$id:$field: '$value_stored' <> '$value_archive'");
                }
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
    protected function diff($array1, $array2) {
        return array_unique(array_merge(
            @array_diff($array1, $array2),
            @array_diff($array2, $array1)
        ));
    }
}
