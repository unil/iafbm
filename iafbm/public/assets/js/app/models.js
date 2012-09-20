/******************************************************************************
 * Business objects
**/

// Models
Ext.define('iafbm.model.Grandeur', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'},
        {name: 'dimsension_symbole', type: 'string'},
        {name: 'unite', type: 'string'},
        {name: 'unite_singulier', type: 'string'},
        {name: 'unite_pluriel', type: 'string'},
        {name: 'unite_symbole', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/grandeurs',
    }
});
Ext.define('iafbm.model.Etatcivil', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/etatscivils',
    }
});
Ext.define('iafbm.model.Genre', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'},
        {name: 'initiale', type: 'string'},
        {name: 'intitule', type: 'string'},
        {name: 'intitule_abreviation', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/genres',
    }
});
Ext.define('iafbm.model.Permis', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'code', type: 'string'},
        {name: 'nom', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/permis',
    }
});
Ext.define('iafbm.model.Canton', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'code', type: 'string'},
        {name: 'nom', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/cantons',
    }
});
Ext.define('iafbm.model.Pays', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'code', type: 'string'},
        {name: 'nom', type: 'string'},
        {name: 'nom_en', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/pays',
    }
});
Ext.define('iafbm.model.Section', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'code', type: 'string'},
        {name: 'nom', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/sections',
    }
});
Ext.define('iafbm.model.Formation', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'abreviation', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/formations',
    }
});
Ext.define('iafbm.model.ActiviteType', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/activites_types',
    }
});
Ext.define('iafbm.model.ActiviteNom', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'abreviation', type: 'string'},
        {name: 'nom', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/activites_noms',
    }
});
Ext.define('iafbm.model.Activite', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'section_id', type: 'int', useNull: true},
        {name: 'activite_nom_id', type: 'int', useNull: true},
        {name: 'activite_type_id', type: 'int', useNull: true},
        {name: 'section_code', type: 'string'},
        {name: 'section_nom', type: 'string'},
        {name: 'activite_type_nom', type: 'string'},
        {name: 'activite_nom_nom', type: 'string'},
        {name: 'activite_nom_abreviation', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/activites',
    }
});
Ext.define('iafbm.model.Rattachement', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/rattachements',
    }
});
Ext.define('iafbm.model.Adresse', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'adresse_type_id', type: 'int', useNull: true},
        {name: 'rue', type: 'string'},
        {name: 'npa', type: 'string'},
        {name: 'lieu', type: 'string'},
        {name: 'pays_id', type: 'int'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/adresses',
    }
});
Ext.define('iafbm.model.AdresseType', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/adresses_types',
    }
});
Ext.define('iafbm.model.Personne', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'personne_type_id', type: 'int', useNull: true},
        {name: 'nom', type: 'string'},
        {name: 'prenom', type: 'string'},
        {name: 'genre_id', type: 'int', useNull: true},
        {name: 'personne_denomination_id', type: 'int', useNull: true},
        {name: 'etatcivil_id', type: 'int', useNull: true},
        {name: 'date_naissance', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'no_avs', type: 'string'},
        {name: 'canton_id', type: 'int', useNull: true},
        {name: 'pays_id', type: 'int', useNull: true},
        {name: 'permis_id', type: 'int', useNull: true},
        {name: 'actif', type: 'boolean', defaultValue: true},
        // Foreign fields
        {name: 'pays_nom', type: 'string'},
        {name: 'pays_code', type: 'string'},
        // Ghost fields
        {name: '_activites', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/personnes',
    }
});
Ext.define('iafbm.model.PersonneType', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/personnes_types',
    }
});
Ext.define('iafbm.model.PersonneDenomination', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'},
        {name: 'nom_masculin', type: 'string'},
        {name: 'nom_feminin', type: 'string'},
        {name: 'abreviation', type: 'string'},
        {name: 'abreviation_masculin', type: 'string'},
        {name: 'abreviation_feminin', type: 'string'},
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/personnes_denominations',
    }
});
Ext.define('iafbm.model.PersonneFormation', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'personne_id', type: 'int'},
        {name: 'formation_id', type: 'int'},
        {name: 'date_these', type: 'date', dateFormat: 'Y-m-d'},
        {name: '_date_these_jour', type: 'int'},
        {name: '_date_these_mois', type: 'int'},
        {name: '_date_these_annee', type: 'int'},
        {name: 'lieu_these', type: 'string'},
        {name: 'commentaire', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/personnes_formations',
    }
});
Ext.define('iafbm.model.PersonneActivite', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'personne_id', type: 'int', useNull: true},
        {name: 'activite_id', type: 'int', useNull: true},
        {name: 'activite_type_id', type: 'int', useNull: true},
        {name: 'activite_nom_id', type: 'int', useNull: true},
        {name: 'activite_nom_abreviation', type: 'string'},
        {name: 'rattachement_id', type: 'int', useNull: true},
        {name: 'rattachement_nom', type: 'string'},
        {name: 'section_id', type: 'int', useNull: true},
        {name: 'taux_activite', type: 'int', useNull: true},
        {name: 'debut', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'fin', type: 'date', dateFormat: 'Y-m-d'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/personnes_activites',
    }
});
Ext.define('iafbm.model.PersonneAdresse', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'personne_id', type: 'int'},
        {name: 'adresse_id', type: 'int'},
        // Foreign 'Adresse' fields
        {name: 'adresse_adresse_type_id', type: 'int'},
        {name: 'adresse_rue', type: 'string'},
        {name: 'adresse_npa', type: 'string'},
        {name: 'adresse_lieu', type: 'string', useNull: true},
        {name: 'adresse_pays_id', type: 'int'},
        {name: 'adresse_telephone', type: 'string'},
        {name: 'adresse_telephone_countrycode', type: 'string'},
        {name: 'defaut', type: 'boolean'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/personnes_adresses',
    }
});
Ext.define('iafbm.model.PersonneEmail', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'personne_id', type: 'int'},
        {name: 'adresse_type_id', type: 'int'},
        {name: 'email', type: 'string'},
        {name: 'defaut', type: 'boolean'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/personnes_emails',
    }
});
Ext.define('iafbm.model.PersonneTelephone', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'personne_id', type: 'int'},
        {name: 'adresse_type_id', type: 'int'},
        {name: 'countrycode', type: 'string'},
        {name: 'telephone', type: 'string'},
        {name: 'defaut', type: 'boolean'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/personnes_telephones',
    }
});
Ext.define('iafbm.model.CommissionMembre', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'actif', type: 'boolean', defaultValue: true},
        {name: 'personne_id', type: 'int'},
        {name: 'commission_id', type: 'int'},
        {name: 'commission_fonction_id', type: 'int'},
        {name: 'fonction_complement', type: 'string'},
        {name: 'personne_denomination_id', type: 'int', useNull: true},
        {name: 'activite_id', type: 'int', useNull: true},
        {name: 'rattachement_id', type: 'int', useNull: true},
        {name: 'version_id', type: 'int', useNull: true},
        // Foreign fields
        {name: 'personne_nom', type: 'string'},
        {name: 'personne_prenom', type: 'string'},
        {name: 'activite_nom_abreviation', type: 'string'},
        {name: 'rattachement_nom', type: 'string'},
        {name: 'commission_nom', type: 'string'},
        {name: 'commission_type_racine', type: 'string'},
        {name: 'commission_etat_nom', type: 'string'},
        {name: 'commission_fonction_nom', type: 'string'},
        // Ghost fields
        {name: '_uptodate', type: 'boolean', defaultValue: true},
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions_membres',
    }
});
Ext.define('iafbm.model.CommissionMembreNonominatif', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'actif', type: 'boolean', defaultValue: true},
        {name: 'nom_prenom', type: 'string'},
        {name: 'commission_id', type: 'int'},
        {name: 'commission_fonction_id', type: 'int'},
        {name: 'fonction_complement', type: 'string'},
        {name: 'personne_denomination_id', type: 'int', useNull: true},
        {name: 'activite_id', type: 'int', useNull: true},
        {name: 'rattachement_id', type: 'int', useNull: true},
        // Foreign fields
        {name: 'activite_nom_abreviation', type: 'string'},
        {name: 'rattachement_nom', type: 'string'},
        {name: 'commission_nom', type: 'string'},
        {name: 'commission_type_racine', type: 'string'},
        {name: 'commission_etat_nom', type: 'string'},
        {name: 'commission_fonction_nom', type: 'string'},
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions_membres_nonominatifs',
    }
});
Ext.define('iafbm.model.Candidat', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'commission_id', type: 'int'},
        {name: 'nom', type: 'string'},
        {name: 'prenom', type: 'string'},
        {name: 'genre_id', type: 'int', useNull: true},
        {name: 'personne_denomination_id', type: 'int', useNull: true},
        {name: 'etatcivil_id', type: 'int', useNull: true},
        {name: 'pays_id', type: 'int', useNull: true},
        {name: 'canton_id', type: 'int', useNull: true},
        {name: 'pays_id', type: 'int', useNull: true},
        {name: 'permis_id', type: 'int', useNull: true},
        {name: 'date_naissance', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'nombre_enfants', type: 'int', useNull: true},
        {name: 'no_avs', type: 'string'},
        {name: 'adresse_pro', type: 'sting'},
        {name: 'npa_pro', type: 'sting'},
        {name: 'lieu_pro', type: 'sting'},
        {name: 'pays_pro_id', type: 'int', useNull: true},
        {name: 'telephone_pro_countrycode', type: 'sting'},
        {name: 'telephone_pro', type: 'sting'},
        {name: 'email_pro', type: 'sting'},
        {name: 'adresse_pri', type: 'sting'},
        {name: 'npa_pri', type: 'sting'},
        {name: 'lieu_pri', type: 'sting'},
        {name: 'pays_pri_id', type: 'int', useNull: true},
        {name: 'telephone_pri_countrycode', type: 'sting'},
        {name: 'telephone_pri', type: 'sting'},
        {name: 'email_pri', type: 'sting'},
        {name: 'adresse_defaut', type: 'sting'},
        {name: 'position_actuelle_fonction', type: 'string'},
        {name: 'position_actuelle_lieu', type: 'string'},
        // Foreign fields
        {name: 'commission_nom', type: 'string'},
        // Ghost fields
        {name: '_adresse_defaut', type: 'string'},
        {name: '_npa_defaut', type: 'string'},
        {name: '_lieu_defaut', type: 'string'},
        {name: '_pays_defaut_id', type: 'int', useName: true},
        {name: '_telephone_defaut_countrycode', type: 'string'},
        {name: '_telephone_defaut', type: 'string'},
        {name: '_email_defaut', type: 'string'},
        {name: '_display', mapping: 0, convert: function(value, record) {
            return [
                record.get('prenom'),
                record.get('nom'),
                record.get('genre_initiale')
            ].join(' ');
        }},
        {name: 'actif', type: 'boolean', defaultValue: true}
    ],
    validations: [
        { field: 'nom', type: 'presence' },
        { field: 'prenom', type: 'presence' },
    ],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/candidats',
    }
});
Ext.define('iafbm.model.CandidatFormation', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'candidat_id', type: 'int'},
        {name: 'formation_id', type: 'int'},
        {name: 'lieu_these', type: 'string'},
        {name: 'date_these', type: 'date', dateFormat: 'Y-m-d'},
        {name: '_date_these_jour', type: 'int'},
        {name: '_date_these_mois', type: 'int'},
        {name: '_date_these_annee', type: 'int'},
        {name: 'commentaire', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/candidats_formations',
    }
});
Ext.define('iafbm.model.Commission', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'termine', type: 'boolean', defaultValue: false},
        {name: 'nom', type: 'string'},
        {name: 'institut', type: 'string'},
        {name: 'commentaire', type: 'string'},
        {name: 'commission_type_id', type: 'int'},
        {name: 'commission_type_nom', type: 'string'},
        {name: 'commission_type_racine', type: 'string'},
        {name: 'commission_etat_id', type: 'int', defaultValue: 1},
        {name: 'commission_etat_nom', type: 'string'},
        {name: 'commission_fonction_nom', type: 'string'},
        {name: 'section_id', type: 'int'},
        {name: 'section_code', type: 'string'},
        {name: 'actif', type: 'boolean', defaultValue: true},
        {name: '_president', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions',
    }
});
Ext.define('iafbm.model.CommissionEtat', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'},
        {name: 'description', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions_etats',
    }
});
Ext.define('iafbm.model.CommissionType', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'},
        {name: 'racine', type: 'string'},
        {name: 'actif', type: 'boolean', defaultValue: true}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions_types',
    }
});
Ext.define('iafbm.model.CommissionFonction', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'},
        {name: 'description', type: 'string'},
        {name: 'position', type: 'int'},
        {name: 'actif', type: 'boolean', defaultValue: true}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions_fonctions',
    }
});
Ext.define('iafbm.model.CommissionCreation', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'commission_id', type: 'int'},
        {name: 'termine', type: 'boolean'},
        {name: 'actif', type: 'boolean', defaultValue: true},
        {name: 'decision', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'preavis', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'autorisation', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'annonce', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'composition', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'composition_validation', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'commentaire', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions_creations',
    }
});
Ext.define('iafbm.model.CommissionCandidatCommentaire', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'actif', type: 'boolean', defaultValue: true},
        {name: 'commission_id', type: 'int'},
        {name: 'termine', type: 'boolean'},
        {name: 'commentaire', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions_candidats_commentaires',
    }
});
Ext.define('iafbm.model.CommissionTravail', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'termine', type: 'boolean'},
        {name: 'primo_loco', type: 'int', useNull: true},
        {name: 'secondo_loco', type: 'int', useNull: true},
        {name: 'tertio_loco', type: 'int', useNull: true},
        {name: 'commentaire', type: 'string'},
        {name: 'actif', type: 'boolean', defaultValue: true}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions_travails',
    }
});
Ext.define('iafbm.model.CommissionTravailEvenement', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'commission_id', type: 'int'},
        {name: 'commission_travail_evenement_type_id', type: 'int'},
        {name: 'date', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'proces_verbal', type: 'boolean'},
        {name: 'duree', type: 'int', useNull: true},
        {name: 'actif', type: 'boolean', defaultValue: true}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions_travails_evenements',
    }
});
Ext.define('iafbm.model.CommissionTravailEvenementType', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions_travails_evenements_types',
    }
});
Ext.define('iafbm.model.CommissionValidation', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'commission_id', type: 'int'},
        {name: 'termine', type: 'boolean'},
        {name: 'decanat_date', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'decanat_etat', type: 'int'},
        {name: 'decanat_commentaire', type: 'string'},
        {name: 'dg_date', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'dg_commentaire', type: 'string'},
        {name: 'cf_date', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'cf_etat', type: 'int'},
        {name: 'cf_commentaire', type: 'string'},
        {name: 'cdir_date', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'cdir_etat', type: 'int'},
        {name: 'cdir_commentaire', type: 'string'},
        {name: 'reception_rapport', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'envoi_proposition_nomination', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'commentaire', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions_validations',
    }
});
Ext.define('iafbm.model.CommissionValidationEtat', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions_validations_etats',
    }
});
Ext.define('iafbm.model.CommissionFinalisation', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'commission_id', type: 'int'},
        {name: 'candidat_id', type: 'int', useNull: true},
        {name: 'termine', type: 'boolean'},
        {name: 'reception_contrat_date', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'reception_contrat_commentaire', type: 'string'},
        {name: 'debut_activite', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'commentaire', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions_finalisations',
    }
});
Ext.define('iafbm.model.CommissionPropositionNomination', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'commission_id', type: 'int'},
        {name: 'candidat_id', type: 'int', useNull: true},
        {name: 'activite_id', type: 'int', useNull: true},
        {name: 'objet', type: 'string'},
        {name: 'contrat_debut', type: 'date', dateFormat: 'Y-m-d', useNull: true},
        {name: 'contrat_debut_au_plus_tot', type: 'boolean'},
        {name: 'contrat_fin', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'charge_horaire', type: 'int', useNull: true},
        {name: 'grandeur_id', type: 'int', useNull: true},
        {name: 'indemnite', type: 'int', useNull: true},
        {name: 'titre_cours', type: 'string'},
        {name: 'grade_obtention_lieu', type: 'string'},
        {name: 'grade_obtention_date', type: 'date', dateFormat: 'Y-m-d', useNull: true},
        {name: 'observations', type: 'string'},
        {name: 'date_preavis_champs', type: 'string'},
        {name: 'date_proposition', type: 'date', dateFormat: 'Y-m-d', useNull: true},
        {name: 'annexe_rapport_commission', type: 'boolean'},
        {name: 'annexe_cahier_des_charges', type: 'boolean'},
        {name: 'annexe_cv_publications', type: 'boolean'},
        {name: 'annexe_declaration_sante', type: 'boolean'},
        {name: 'imputation_fonds', type: 'string'},
        {name: 'imputation_centre_financier', type: 'string'},
        {name: 'imputation_unite_structurelle', type: 'string'},
        {name: 'imputation_numero_projet', type: 'string'},
        {name: 'actif', type: 'boolean', defaultValue: true}
    ],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions_propositions_nominations',
    }
});
Ext.define('iafbm.model.Version', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'created', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'creator', type: 'string'},
        {name: 'table_name', type: 'string'},
        {name: 'table_name', type: 'string'},
        {name: 'id_field_name', type: 'string'},
        {name: 'id_field_value', type: 'string'},
        {name: 'field_name', type: 'string'},
        {name: 'old_value', type: 'string'},
        {name: 'new_value', type: 'string'},
        {name: 'model_name', type: 'string'},
        {name: 'operation', type: 'string'},
        {name: 'commentaire', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/versions',
    }
});
Ext.define('iafbm.model.VersionData', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'version_id', type: 'int'},
        {name: 'field_name', type: 'string'},
        {name: 'old_value', type: 'string'},
        {name: 'new_value', type: 'string'},
        // Foreign 'Version' fields
        {name: 'version_created', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'version_creator', type: 'string'},
        {name: 'version_table_name', type: 'string'},
        {name: 'version_id_field_name', type: 'string'},
        {name: 'version_id_field_value', type: 'string'},
        {name: 'version_model_name', type: 'string'},
        {name: 'version_operation', type: 'string'},
        {name: 'version_commentaire', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/versions_data',
    }
});
Ext.define('iafbm.model.VersionRelation', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'version_id', type: 'int'},
        {name: 'table_name', type: 'string'},
        {name: 'model_name', type: 'string'},
        {name: 'id_field_name', type: 'string'},
        {name: 'id_field_value', type: 'string'},
        // Foreign 'Version' fields
        {name: 'version_created', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'version_creator', type: 'string'},
        {name: 'version_table_name', type: 'string'},
        {name: 'version_id_field_name', type: 'string'},
        {name: 'version_id_field_value', type: 'string'},
        {name: 'version_model_name', type: 'string'},
        {name: 'version_operation', type: 'string'},
        {name: 'version_commentaire', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/versions_relations',
    }
});

// Stores: creates one store per existing model
for (model in iafbm.model) {
    Ext.define(['iafbm', 'store', model].join('.'), {
        extend: 'Ext.ia.data.Store',
        model: ['iafbm', 'model', model].join('.'),
    });
}