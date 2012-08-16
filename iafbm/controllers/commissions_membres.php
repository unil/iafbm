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

    function export() {
        // TODO
        // Fields: 'Dénomination', 'Fonction', 'Complément de fonction', 'Nom et prénom', 'Type adresse', 'Rue', 'NPA', 'Ville', 'Pays', 'Type téléphone', 'Indicatif', 'Numéro', 'Type email', 'Email'
die($this->params['id']);
        $commission_id = @$this->params['id'];
        if (!$commission_id) throw new xException("id parameter missing, please provide a 'commission' id", 400);
        // Fetches 'commission_membre' rows
        $data = xModel::load('commission_membre', array(
            'commission_id' => $commission_id,
            //'xjoin' => null
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
            $fields = array('id',
                'fonction_complement',
                'personne_id_unil',
                'personne_id_chuv',
                'personne_id_adifac',
                'personne_nom',
                'personne_prenom',
                'personne_date_naissance',
                'personne_no_avs',
                'personne_pays_nom',
                'personne_pays_nom_en',
                'commission_fonction_nom',
                'commission_fonction_description',
                'activite_nom_nom',
                'activite_nom_abreviation',
                'rattachement_nom',
                'rattachement_abreviation',
                'commission_nom',
                'commission_commentaire',
                '_uptodate',
                'adresse_rue',
                'adresse_npa',
                'adresse_lieu',
                'adresse_type_nom',
                'adresse_pays_nom', // Should be translated to; 'adresse_pays_nom'
                'adresse_pays_en'   // Should be translated to; 'adresse_pays_nom_en'
            );
            //$d = xUtil::filter_keys($d, $fields);
        }
        return $data;
    }
}
