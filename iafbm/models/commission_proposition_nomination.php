<?php

class CommissionPropositionNominationModel extends iaModelMysql {

    var $versioning = false;

    var $table = 'commissions_propositions_nominations';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'commission_id' => 'commission_id',
        'candidat_id' => 'candidat_id',
        'objet' => 'objet',
        'activite_id' => 'activite_id',
        'contrat_debut' => 'contrat_debut',
        'contrat_debut_au_plus_tot' => 'contrat_debut_au_plus_tot',
        'contrat_fin' => 'contrat_fin',
        'indemnite' => 'indemnite',
        'charge_horaire' => 'charge_horaire',
        'grandeur_id' => 'grandeur_id',
        'titre_cours' => 'titre_cours',
        'observations' => 'observations',
        'date_preavis_champs' => 'date_preavis_champs',
        'date_proposition' => 'date_proposition',
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
        'commission_travail' => 'LEFT JOIN commissions_travails ON (commissions_propositions_nominations.commission_id = commissions_travails.commission_id)',
        'commission_validation' => 'LEFT JOIN commissions_validations ON (commissions_propositions_nominations.commission_id = commissions_validations.commission_id)',
        'candidat' => 'LEFT JOIN candidats ON (commissions_propositions_nominations.candidat_id = candidats.id)',
        'activite' => 'LEFT JOIN activites ON (commissions_propositions_nominations.activite_id = activites.id)',
        'grandeur' => 'LEFT JOIN grandeurs ON (commissions_propositions_nominations.grandeur_id = grandeurs.id)'
    );

    var $join = array('commission', 'grandeur');

    var $validation = array(
        'commission_id' => 'mandatory',
    );

    var $archive_foreign_models = array(
    );
}
