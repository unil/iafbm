<?php

/**
 * @package iafbm
 * @subpackage controller
 */
class PersonnesDenominationsController extends iaExtRestController {
    var $model = 'personne_denomination';
    var $allow = array('get');

    /**
     * Returns an epicene (gender-aware) nom.
     */
    function _make_nom() {
        $info = $this->_make_label();
        return $info['nom'];
    }

    /**
     * Returns an epicene (gender-aware) abreviation.
     */
    function _make_abreviation() {
        $info = $this->_make_label();
        return $info['abreviation'];
    }

    /**
     * Returns 'denomination' information, according 'epicene' language (masculine, feminine).
     * Parameter:
     * - personne_id: id of the 'personne'
     * - (denomination_id: force a certain 'denomination')
     * @return array
     */
    function _make_label() {
        $personne_id = @$this->params['personne_id'];
        if (!$personne_id) throw new xException('Missing personne_id parameter', 400);
        $personne = xModel::load('personne', array(
            'id'=>$personne_id,
            'xjoin' => 'genre,personne_denomination',
            'xversion' => @$this->params['xversion'],
        ))->get(0);
        if (!$personne) throw new xException("Personne id:{$personne_id} not found", 404);
        // Forces denomination if applicable
        if (@$this->params['denomination_id']) {
            $denomination = xModel::load('personne_denomination', array(
                'id' => $this->params['denomination_id'],
                // FIXME: Because personne_denomination was implemented later,
                //        denomination is empty for commission_membres that were last
                //        updated before denomination implementation.
                //        Therefore, the developer disabled denomination versioning.
                //'xversion' => $this->params['xversion']
            ))->get(0);
            foreach ($denomination as $field => $value) {
                $personne["personne_denomination_{$field}"] = $value;
            }
        }
        // Defines female/male terms to use
        if (!$personne['personne_denomination_id'] || !$personne['genre_id']) {
            $determinant = null;
            $appellation = null;
            $titre = null;
            $abreviation = null;
        } elseif ($personne['genre_initiale']=='F') {
            $determinant = 'la';
            $appellation = $personne['genre_intitule'];
            $titre = $personne['personne_denomination_nom_feminin'];
            $abreviation = $personne['personne_denomination_abreviation_feminin'];
        } elseif ($personne['genre_initiale']=='H') {
            $determinant = 'le';
            $appellation = $personne['genre_intitule'];
            $titre = $personne['personne_denomination_nom_masculin'];
            $abreviation = $personne['personne_denomination_abreviation_masculin'];
        }
        // No $titre for Madame/Monsieur (avoids Madame la Madame)
        if ($personne['personne_denomination_id'] == 3) {
            $determinant = null;
            $titre = null;
        }
        // Returns gender-aware denomination information
        return array(
            'nom' => trim("{$appellation} {$determinant} {$titre}"),
            'abreviation' => $abreviation
        );
    }
}