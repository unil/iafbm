<?php

class CommissionPropositionNominationModel extends iaModelMysql {

    var $versioning = false;

    var $table = 'commissions_propositions_nominations';

    var $mapping = array(
        // FIXME: Remove fields taken from candidat/commission that are read-only (not changeable by user)
        'id' => 'id',
        'actif' => 'actif',
        'commission_id' => 'commission_id',
        'section_id' => 'section_id',
        'objet' => 'objet',
        //'titre_propose?' => 'titre_propose?',
        'contrat_debut' => 'contrat_debut',
        'contrat_fin' => 'contrat_fin',
        'contrat_taux' => 'contrat_taux',
        'indemnite' => 'indemnite',
        'denomination_id' => 'denomination_id',
        'nom' => 'nom',
        'prenom' => 'prenom',
        'email' => 'email',
        'etatcivil_id' => 'etatcivil_id',
        'date_naissance' => 'date_naissance',
        'pays_id' => 'pays_id',
        'canton_id' => 'canton_id',
        'permis_id' => 'permis_id',
        'position_actuelle_fonction' => 'position_actuelle_fonction',
        'discipline_generale' => 'discipline_generale',
        'formation_id' => 'formation_id',
        'observations' => 'observations'
    );

    var $primary = array('id');

    var $joins = array(
        'commission' => 'LEFT JOIN commissions ON (commissions_propositions_nominations.commission_id = commissions.id)',
        'section' => 'LEFT JOIN sections ON (commissions_propositions_nominations.section_id = sections.id)',
        'denomination' => 'LEFT JOIN denominations ON (commissions_propositions_nominations.denomination_id = denominations.id)',
        'etatcivil' => 'LEFT JOIN etatscivils ON (commissions_propositions_nominations.etatcivil_id = etatscivils.id)',
        'pays' => 'LEFT JOIN pays ON (commissions_propositions_nominations.pays_id = pays.id)',
        'canton' => 'LEFT JOIN cantons ON (commissions_propositions_nominations.canton_id = cantons.id)',
        'permis' => 'LEFT JOIN permis ON (commissions_propositions_nominations.permis_id = permis.id)',
        'formation' => 'LEFT JOIN formations ON (commissions_propositions_nominations.formation_id = formation.id)',
    );

    var $join = array('pays');

    var $validation = array(
        'nom' => 'mandatory',
        'prenom' => 'mandatory',
        'email_pro' => 'email',
        'email_pri' => 'email'
    );

    var $archive_foreign_models = array(
    );
}
