<?php

class CommissionPropositionNominationModel extends iaModelMysql {

    var $versioning = false;

    var $table = 'commissions_propositions_nominations';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'commission_id' => 'commission_id',
        'candidat_id' => 'candidat_id',
        'institut' => 'institut',
        'objet' => 'objet',
        'activite_id' => 'activite_id',
        'contrat_debut' => 'contrat_debut',
        'contrat_debut_au_plus_tot' => 'contrat_debut_au_plus_tot',
        'contrat_fin' => 'contrat_fin',
        'contrat_taux' => 'contrat_taux',
        'indemnite' => 'indemnite',
        'charge_horaire' => 'charge_horaire',
        'discipline_generale' => 'discipline_generale',
        'observations' => 'observations',
        'grade_obtention_lieu' => 'grade_obtention_lieu',
        'grade_obtention_date' => 'grade_obtention_date',
        'preavis' => 'preavis',
        'formation_id' => 'formation_id',
        'annexe_rapport_commission' => 'annexe_rapport_commission',
        'annexe_cahier_des_charges' => 'annexe_cahier_des_charges',
        'annexe_cv_publications' => 'annexe_cv_publications',
        'annexe_declaration_sante' => 'annexe_declaration_sante',
        'imputation_fonds' => 'imputation_fonds',
        'imputation_centre_financier' => 'imputation_centre_financier',
        'imputation_unite_structurelle' => 'imputation_unite_structurelle',
        'imputation_numero_projet' => 'imputation_numero_projet',
    );

    var $primary = array('id');

    var $joins = array(
        'commission' => 'LEFT JOIN commissions ON (commissions_propositions_nominations.commission_id = commissions.id)',
        'candidat' => 'LEFT JOIN candidats ON (commissions_propositions_nominations.candidat_id = candidats.id)',
        'activite' => 'LEFT JOIN activites ON (commissions_propositions_nominations.activite_id = activites.id)',
        'formation' => 'LEFT JOIN formations ON (commissions_propositions_nominations.formation_id = formations.id)',
    );

    var $join = array('commission');

    var $validation = array(
        'commission_id' => 'mandatory',
    );

    var $archive_foreign_models = array(
    );
}
