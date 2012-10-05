<?php

require_once('commissions.php');

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
            'commission_fonction_id' => array(9, 11),
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
            'commission_fonction_id' => array(9, 11)
        ));
        return $this->getAll();
    }

    function export() {
        $commission_id = @$this->params['id'];
        if (!$commission_id) throw new xException("id parameter missing, please provide a 'commission' id", 400);
        // Fetches 'commission_membre' rows
        $data = xModel::load('commission_membre', array(
            'commission_id' => $commission_id,
            'xjoin' => 'personne,personne_denomination,commission_fonction'
        ))->get();
        // Adds versioned 'adresse' model row for each 'commission_membre' row
        foreach ($data as &$d) {
            $adresses = xModel::load('personne_adresse', array(
                'personne_id' => $d['personne_id'],
                'xversion' => $d['version_id'],
                'xjoin' => array('adresse', 'adresse_type', 'adresse_pays')
            ))->get();
            // Discards adresses that are not set as default
            // This has to be done after retrieval because of xversion
            foreach ($adresses as $i => $a) {
                if (!$a['actif'] || !$a['defaut']) unset($adresses[$i]);
            }
            // Checks that only one default adresse exists
            if (count($adresses) > 1) throw new xException(
                "Personne id {$d['personne_id']} has multiple default adresses"
            );
            // Adds adresse fields to 'commission_membre' model row
            if($adresse = array_shift($adresses)) {
                // Adds fields and values to personne row
                foreach ($adresse as $field => $value) $d[$field] = $value;
            } else {
                // Adds fields with empty values to personne row
                // for data structure consistency
                $fields = xModel::load(
                    'personne_adresse'
                )->foreign_mapping(array('adresse', 'adresse_type', 'adresse_pays'));
                foreach ($fields as $field => $dbfield) $d[$field] = null;
            }
            // Filters/renames/reorders fields to export
            // (TODO: move this outside this foreach loop)
            $fields = array(
                'id',
                'commission_fonction_nom',
                'personne_denomination_nom',
                'personne_prenom',
                'personne_nom',
                'adresse_rue',
                'adresse_npa',
                'adresse_lieu',
                //'TODO: default telephone',
                //'TODO: default email',
            );
            $d = xUtil::filter_keys($d, $fields);
        }
        return $data;
    }
}
