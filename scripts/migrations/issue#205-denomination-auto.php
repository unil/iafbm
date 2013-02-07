<?php

require_once(dirname(__file__).'/../Script.php');

class iafbmIssue205 extends iafbmScript {

    /**
     * Maps activite_nom_id => abreviation_id
     * @var array
     */
    protected $mapping = array(
        // Docteur
        'PD' => 2,
        'MER1' => 2,
        // Professeur
        'PAST en PTC' => 1,
        'PAST' => 1,
        'PAS' => 1,
        'PO' => 1,
        'PI' => 1,
        'PTIT' => 1,
        'PAS ad personam' => 1,
        'PO ad personam' => 1,
    );

    protected $unprocessed = array(
        'personne' => array(),
        'activite' => array()
    );

    function run() {
        // Confirmation
        $this->confirm("This action will modify every 'personne' denomination. Are you sure?");
        // Process
        $t = new xTransaction();
        $t->start();
        $this->update_records($t);
        $t->end();
        // Summary
        $this->log('Summary');
        $this->log(implode(' ', array(
            count($this->unprocessed['personne']),
            'unprocessed personnes ids:',
            implode(',', $this->unprocessed['personne'])
        )), 1);
        $this->log(implode(' ', array(
            count($this->unprocessed['activite']),
            'unprocessed activites:',
            implode(',', $this->unprocessed['activite'])
        )), 1);
    }

    function update_records(xTransaction $t) {
        $processed_activites = array();
        $this->log('Processing...');
        // Processes every personne
        $personnes = $t->execute(xModel::load('personne'), 'get');
        foreach ($personnes as $personne) {
            // Looking into every personne activite
            $activites = $t->execute(xModel::load('personne_activite', array(
                'personne_id' => $personne['id']
            )), 'get');
            // Determines highest denomination according activites
            $denomination_id = null;
            foreach ($activites as $activite) {
                $activite_abr = $activite['activite_nom_abreviation'];
                $denomination_id = @$this->mapping[$activite_abr];
                // Skips if activite is not in mapping
                if (@!$denomination_id) continue;
                $processed_activites[] = $activite_abr;
                // Keeps highest denomination
                // (eg. lowest id, according table data)
                $denomination_id = min($denomination_id, $this->mapping[$activite_abr]);
            }
            // Skips if no mappable activite found
            if (!$denomination_id) {
                $this->unprocessed['personne'][] = $personne['id'];
                continue;
            }
            // Updates personne denomination_id
            $this->log("Updating: Personne id:{$personne['id']} => Dénomination id:{$denomination_id} ", 1);
            $t->execute(xModel::load('personne', array(
                'id' => $personne['id'],
                'personne_denomination_id' => $denomination_id
            )), 'post');
        }
        // Computes unused $mapping activites
        $this->unprocessed['activite'] = array_diff(array_keys($this->mapping), array_unique($processed_activites));
    }
}

new iafbmIssue205();