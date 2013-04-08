<?php

require_once('commissions.php');

/**
 * @package iafbm
 * @subpackage controller
 */
class CommissionsMembresController extends AbstractCommissionController {

    var $model = 'commission_membre';

    var $sort_fields_substitutions = array(
        'activite_id' => array(
            'field' => 'activite_nom_nom',
            'join' => 'activite,activite_nom'
        ),
        'rattachement_id' => 'rattachement_nom',
        'commission_fonction_id' => 'commission_fonction_position',
        // Personne:Commission Form:
        // No need to define substitutions as long
        // as CommissionMembreModel.xjoin contains all possible joins.
        // Except for 'commission_etat_nom':
        // we want to sort it on id field
        'commission_etat_nom' => 'commission_etat_id'
    );

    function exportAction() {
        // TODO: Create a common (and factorized) export controller (as for print)
        //       The export controller MUST factorize export "modes"
        //       (eg. mac/windows encodings and default CSV flavours)
        // Ouputs HTTP header for download
        $filename = @"export-commission-membres-{$this->params['id']}.csv";
        header('Content-Type: application/csv');
        header("Content-Disposition: attachment; filename={$filename}");
        // Print generated CSV
        print xFront::load('api',
            array_merge(array('xformat'=>'csv'), $this->params)
        )->encode($this->export());
        exit;
    }

    /**
     * Returns unified commission_membre & commission_membre_nonominatif models.
     * Fields diff (eg. that exist in the other model) are set to null.
     */
    function getAll() {
        // Computes a subset of valid joins for both models
        $join_nominatifs = array_intersect(
            array_keys(xModel::load('commission_membre')->joins),
            xModel::load('commission_membre', $this->params)->join
        );
        $join_nonominatifs = array_intersect(
            array_keys(xModel::load('commission_membre_nonominatif')->joins),
            xModel::load('commission_membre_nonominatif', $this->params)->join
        );
        // Computes a subset of valid order fields for both models
        $order_nominatifs = array_intersect(
            xModel::load('commission_membre', $this->params)->order_by,
            array_merge(
                array_keys(xModel::load('commission_membre')->mapping),
                array_keys(xModel::load('commission_membre')->foreign_mapping($join_nominatifs))
            )
        );
        $order_nonominatifs = array_intersect(
            xModel::load('commission_membre_nonominatif', $this->params)->order_by,
            array_merge(
                array_keys(xModel::load('commission_membre_nonominatif')->mapping),
                array_keys(xModel::load('commission_membre_nonominatif')->foreign_mapping($join_nonominatifs))
            )
        );
        // Determines common fields between both models, according active joins
        $fields = array_unique(array_merge(
            array_merge(
                array_keys(xModel::load('commission_membre', $this->params)->mapping),
                array_keys(xModel::load('commission_membre', $this->params)->foreign_mapping($join_nominatifs))
            ),
            array_merge(
                array_keys(xModel::load('commission_membre_nonominatif', $this->params)->mapping),
                array_keys(xModel::load('commission_membre_nonominatif', $this->params)->foreign_mapping($join_nonominatifs))
            )
        ));
        // Fetches 'membres' and unifies fields names
        $membres = xModel::load('commission_membre', array_merge(
            $this->params,
            array('xjoin' => $join_nominatifs),
            array('xorder_by' => $order_nominatifs)
        ))->get();
        foreach ($membres as &$membre) {
            // Concatenates fields 'nom' & 'prenom' into 'nom_prenom'
            $membre['nom_prenom'] = "{$membre['personne_prenom']} {$membre['personne_nom']}";
            // Fills unexisting fields with null
            $membre = xUtil::array_merge(
                array_fill_keys($fields, null),
                $membre
            );
            // Sets ghost '_type' field
            $membre['_type'] = 'nominatif';
        }
        // Fetches 'membres non nominatifs' and unifies fields names
        $membres_nonominatifs = xModel::load('commission_membre_nonominatif', array_merge(
            $this->params,
            array('xjoin' => $join_nonominatifs),
            array('xorder_by' => $order_nonominatifs)
        ))->get();
        foreach ($membres_nonominatifs as &$membre) {
            // Fills unexisting fields with null
            $membre = xUtil::array_merge(
                array_fill_keys($fields, null),
                $membre
            );
            // Sets ghost '_type' field
            $membre['_type'] = 'non-nominatif';
        }
        return xUtil::array_merge($membres, $membres_nonominatifs);
    }

    /**
     * Returns commission 'membres' (eg. not 'A entendre' or 'Invité')
     * @see getAll()
     */
    function getMembres() {
        $this->params = xUtil::array_merge($this->params, array(
            'commission_fonction_id' => array(3, 9, 11),
            'commission_fonction_id_comparator' => 'NOT IN',
        ));
        return $this->getAll();
    }

    /**
     * Returns commission 'membres non-nominatifs' (eg. only 'A entendre' or 'Invité')
     * @see getAll()
     */
    function getNonMembres() {
        $this->params = xUtil::array_merge($this->params, array(
            'commission_fonction_id' => array(3, 9, 11)
        ));
        return $this->getAll();
    }

    function export() {
        // Export config: order and columns names
        $export_fields = array(
            'Fonction' => 'commission_fonction_nom',
            'Dénomination' => 'personne_denomination_nom',
            'Prénom' => 'personne_nom',
            'Nom' => 'personne_prenom',
            'Adresse' => 'adresse_rue',
            'Code postal' => 'adresse_npa',
            'Ville' => 'adresse_lieu',
            'Téléphone' => 'telephone',
            'Email' => 'email',
        );
        // Manages params
        $commission_id = @$this->params['id'];
        if (!$commission_id) throw new xException("id parameter missing, please provide a 'commission' id", 400);
        // Fetches 'commission_membre' rows
        $data = xModel::load('commission_membre', array(
            'commission_id' => $commission_id,
            'xjoin' => 'personne,personne_denomination,commission_fonction',
            'xorder_by' => 'commission_fonction_position',
            'xorder' => 'ASC'
        ))->get();
        // Adds 'epicene' denomination fields
        foreach ($data as &$d) {
            $d['personne_denomination_nom'] = xController::load('personnes_denominations', array(
                'personne_id' => $d['personne_id'],
                'denomination_id' => $d['personne_denomination_id'],
                'xversion' => $d['version_id']
            ))->_make_nom();
        }
        // Adds versioned 'adresse', 'telephone' and 'email' model rows
        // for each 'commission_membre' row.
        // Array structure: [modelname string] => [xjoin array]
        $foreign = array(
            'personne_adresse' => array('adresse', 'adresse_type', 'adresse_pays'),
            'personne_telephone' => array(),
            'personne_email' => array(),
        );
        foreach ($data as &$d) {
            foreach ($foreign as $model => $join) {
                $items = xModel::load($model, array(
                    'personne_id' => $d['personne_id'],
                    'xversion' => $d['version_id'],
                    'xjoin' => $join
                ))->get();
                // Discards items that are not set as default
                // This has to be done after retrieval because of xversion
                foreach ($items as $i => $a) {
                    if (!$a['actif'] || !$a['defaut']) unset($items[$i]);
                }
                // Checks that only one default item exists
                if (count($items) > 1) throw new xException(
                    "Personne id {$d['personne_id']} has multiple default {$model}"
                );
                // Adds item fields to 'commission_membre' model row
                if ($item = array_shift($items)) {
                    // Adds fields and values to personne row
                    foreach ($item as $field => $value) $d[$field] = $value;
                } else {
                    // Adds fields with empty values to personne row
                    // for data structure consistency
                    $fields = array_merge(
                        array_keys(xModel::load($model)->foreign_mapping($join)),
                        array_keys(xModel::load($model)->mapping)
                    );
                    foreach ($fields as $field) if(@!$d[$field]) $d[$field] = null;
                }
            }
            // Merges 'countrycode'+'telephone' into 'telephone'
            $d['telephone'] = $d['countrycode'] ? "+{$d['countrycode']} {$d['telephone']}" : null;
            // Filters fields to export
            $d = xUtil::filter_keys($d, $export_fields);
            // Sorts fields
            $sorter = array_values($export_fields);
            $d = array_merge(array_flip($sorter), $d);
            // Renames fields with readable-names
            $d = array_combine(array_keys($export_fields), array_values($d));
        }
        return $data;
    }
}
