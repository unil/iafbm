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

    function export() {
        // TODO
        // Fields: 'Dénomination', 'Fonction', 'Complément de fonction', 'Nom et prénom', 'Type adresse', 'Rue', 'NPA', 'Ville', 'Pays', 'Type téléphone', 'Indicatif', 'Numéro', 'Type email', 'Email'
        $commission_id = @$this->params['id'];
        if (!$commission_id) throw new xException("id parameter missing, please provide a 'commission' id", 400);
        // Fetches 'commission_membre' rows
        $data = xModel::load('commission_membre', array(
            'commission_id' => $commission_id
        ))->get();
        // Adds versioned 'adresse' model row for each 'commission_membre' row
        foreach ($data as &$d) {
            $adresses = xModel::load('personne_adresse', array(
                'personne_id' => $d['personne_id'],
                'xversion' => $d['version_id']
            ))->get();
xUtil::pre($adresse);
            // Discards adresses that are not set as default
            // This has to be done after retrieval because of xversion
            foreach ($adresses as $i => $a) if (!$a['default']) unset($adresses[$i]);
            // Checks that only one default adresse exists
            if (count($adresses) > 1) throw new xException(
                "Personne id {$d['personne_id']} have multiple default adresses"
            );
            // Adds adresse fields to personne row
            $adresse = array_shift($adresses);
            foreach ($adresse as $field => $value) $d[$field] = $value;
        }
        return $data;
    }
}