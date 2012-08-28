<?php

class CommissionPropositionNominationModel extends iaModelMysql {

    var $versioning = false;

    var $table = 'commissions_propositions_nominations';

    var $mapping = array(
        // FIXME: Remove fields taken from candidat/commission that are read-only (not changeable by user)
        'id' => 'id',
        'actif' => 'actif',
        'commission_id' => 'commission_id',
        'candidat_id' => 'candidat_id',
        'objet' => 'objet',
        //'titre_propose?' => 'titre_propose?',
        'contrat_debut' => 'contrat_debut',
        'contrat_fin' => 'contrat_fin',
        'contrat_taux' => 'contrat_taux',
        'indemnite' => 'indemnite',
        'observations' => 'observations'
    );

    var $primary = array('id');

    var $joins = array(
        'commission' => 'LEFT JOIN commissions ON (commissions_propositions_nominations.commission_id = commissions.id)',
        'candidat' => 'LEFT JOIN candidats ON (commissions_propositions_nominations.candidat_id = candidats.id)',
    );

    var $join = array('commission');

    var $validation = array(
        'commission_id' => 'mandatory',
    );

    var $archive_foreign_models = array(
    );
}
