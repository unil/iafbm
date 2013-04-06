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
        'discipline_generale' => 'discipline_generale',
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
        'candidat' => array('candidat_id' => 'id'),
        'activite' => array('activite_id' => 'id'),
        'grandeur' => array('grandeur_id' => 'id')
    );

    // Self-documentation
    var $description = 'propositions de nominations';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'commission_id' => 'identifiant de la commission',
        'candidat_id' => 'identifiant du candidat',
        'objet' => 'objet de la proposition',
        'discipline_generale' => 'discipline générale',
        'activite_id' => 'identifiant de l\'activité proposée',
        'contrat_debut' => 'date de début du contrat',
        'contrat_debut_au_plus_tot' => 'le contrat débute au plus tôt',
        'contrat_fin' => 'date de fin du contrat',
        'indemnite' => 'indemnité (en CHF)',
        'charge_horaire' => 'charge horaire',
        'grandeur_id' => 'identifiant de la grandeur de la change horaire',
        'titre_cours' => 'titre du cours',
        'observations' => 'observations',
        'date_preavis_champs' => 'date de préavis (cf. validation de rapport)',
        'date_proposition' => 'date de la proposition',
        'annexe_rapport_commission' => 'rapport de commission reçu',
        'annexe_cahier_des_charges' => 'cahier des charges reçu',
        'annexe_cv_publications' => 'cv et liste de publications reçus',
        'annexe_declaration_sante' => 'déclaration de santé reçue',
        'imputation_fonds' => 'imputation des fonds',
        'imputation_centre_financier' => 'imputation du centre_financier imputé',
        'imputation_unite_structurelle' => 'unite structurelle imputée',
        'imputation_numero_projet' => 'numéro de projet imputé'
    );
}
