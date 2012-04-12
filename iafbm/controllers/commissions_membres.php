<?php

require_once('commissions.php');

class CommissionsMembresController extends AbstractCommissionController {

    var $model = 'commission_membre';

    var $query_fields = array(
        'personne_nom',
        'personne_prenom',
        'personne_personne_type_nom',
        'personne_genre_nom',
        'personne_date_naissance',
        'personne_pays_nom',
        'personne_canton_nom',
        'activite_nom_nom',
        'activite_section_code',
        'rattachement_abreviation',
        'rattachement_nom',
        'rattachement_section_code',
        'commission_nom',
        'commission_commission_type_nom',
        'commission_commission_etat_nom',
        'commission_fonction_nom',
        'commission_section_nom',
        'commission_commentaire'
    );
    var $query_transform = array(
        'date_naissance' => 'date,date-binomial'
    );
    var $query_join = 'personne,pays,genre,commission_fonction,activite,activite_nom,rattachement,commission,commission_type,commission_etat,section';

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
}