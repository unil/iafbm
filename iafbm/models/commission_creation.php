<?php

/**
 * @package iafbm
 * @subpackage model
 */
class CommissionCreationModel extends iaModelMysql {

    var $table = 'commissions_creations';

    var $mapping = array(
        'id' => 'id',
        'commission_id' => 'commission_id',
        'termine' => 'termine',
        'decision' => 'date_decision',
        'preavis_decanat' => 'date_preavis_decanat',
        'etat_preavis_decanat' => 'etat_preavis_decanat',
        'preavis_ccp' => 'date_preavis_ccp',
        'etat_preavis_ccp' => 'etat_preavis_ccp',
        'preavis_cpa' => 'date_preavis_cpa',
        'etat_preavis_cpa' => 'etat_preavis_cpa',
        'autorisation' => 'date_autorisation',
        'etat_autorisation' => 'etat_autorisation',
        'annonce' => 'date_annonce',
        'composition' => 'date_composition',
        'composition_validation' => 'date_composition_validation',
        'commentaire' => 'commentaire',
        'actif' => 'actif',
    );

    var $primary = array('id');

    var $joins = array(
        'commission' => 'LEFT JOIN commissions ON (commissions_creations.commission_id = commissions.id)'
    );

    var $join = 'commission';

    var $validation = array();

    // Self-documentation
    var $description = 'phase de création des commissions';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'commission_id' => 'identifiant de la commission',
        'termine' => 'phase terminée',
        'decision' => 'date de décision du Décanat',
        'preavis' => 'date du préavis positif CPA',
        'autorisation' => 'date de l\'autorisation du CDir',
        'annonce' => 'date de l\'annonce dans les journaux',
        'composition' => 'date de la composition',
        'composition_validation' => 'date de validation de la composition par le vice recteur',
        'commentaire' => 'commentaire'
    );
}
