/******************************************************************************
 * Business objects
**/

// Models
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
        {name: 'genre', type: 'string'},
        {name: 'genre_short', type: 'string'},
        {name: 'intitule', type: 'string'},
        {name: 'intitule_short', type: 'string'}
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
Ext.define('iafbm.model.TitreAcademique', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'abreviation', type: 'string'},
        {name: 'nom', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/titres-academiques',
    }
});
Ext.define('iafbm.model.FonctionHospitaliere', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/fonctions-hospitalieres',
    }
});
Ext.define('iafbm.model.Departement', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/departements',
    }
});
Ext.define('iafbm.model.Adresse', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'adresse-type_id', type: 'string'},
        {name: 'rue', type: 'string'},
        {name: 'npa', type: 'string'},
        {name: 'lieu', type: 'string'},
        {name: 'pays_id', type: 'int'},
        {name: 'telephone_countrycode', type: 'string'},
        {name: 'telephone', type: 'string'}
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
        url: x.context.baseuri+'/api/adresses-types',
    }
});
Ext.define('iafbm.model.Personne', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'personne-type_id', type: 'int', useNull: true},
        {name: 'nom', type: 'string'},
        {name: 'prenom', type: 'string'},
        {name: 'genre_id', type: 'int', useNull: true},
        {name: 'date_naissance', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'no_avs', type: 'string'},
        {name: 'canton_id', type: 'int', useNull: true},
        {name: 'pays_id', type: 'int', useNull: true},
        {name: 'permis_id', type: 'int', useNull: true},
        {name: 'actif', type: 'boolean', defaultValue: true}
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
        url: x.context.baseuri+'/api/personnes-types',
    }
});
Ext.define('iafbm.model.PersonneFormation', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'personne_id', type: 'int'},
        {name: 'formation_id', type: 'int'},
        {name: 'date_these', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'lieu_these', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/personnes-formations',
    }
});
Ext.define('iafbm.model.PersonneFonction', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'personne_id', type: 'int', useNull: true},
        {name: 'section_id', type: 'int', useNull: true},
        {name: 'titre-academique_id', type: 'int', useNull: true},
        {name: 'titre-academique_abreviation', type: 'string'},
        {name: 'taux_activite', type: 'int', useNull: true},
        {name: 'date_contrat', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'debut_mandat', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'fonction-hospitaliere_id', type: 'int', useNull: true},
        {name: 'departement_id', type: 'int', useNull: true},
        {name: 'departement_nom', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/personnes-fonctions',
    }
});
Ext.define('iafbm.model.PersonneAdresse', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'personne_id', type: 'int'},
        {name: 'adresse_id', type: 'int'},
        // Foreign 'Adresse' fields
        {name: 'adresse_adresse-type_id', type: 'int'},
        {name: 'adresse_rue', type: 'string'},
        {name: 'adresse_npa', type: 'string'},
        {name: 'adresse_lieu', type: 'string'},
        {name: 'adresse_pays_id', type: 'int'},
        {name: 'adresse_telephone', type: 'string'},
        {name: 'adresse_telephone_countrycode', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/personnes-adresses',
    }
});
Ext.define('iafbm.model.PersonneEmail', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'personne_id', type: 'int'},
        {name: 'adresse-type_id', type: 'int'},
        {name: 'email', type: 'string'},
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/personnes-emails',
    }
});
Ext.define('iafbm.model.CommissionMembre', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'personne_id', type: 'int'},
        {name: 'commission_id', type: 'int'},
        {name: 'fonction_id', type: 'int'},
        {name: 'titre-academique_id', type: 'int', useNull: true},
        {name: 'departement_id', type: 'int', useNull: true},
        {name: 'personne_nom', type: 'string'},
        {name: 'personne_prenom', type: 'string'},
        {name: 'titre', type: 'string', defaultValue: 'Prof.'},
        {name: 'actif', type: 'boolean', defaultValue: true}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions-membres',
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
        {name: 'etatcivil_id', type: 'int', useNull: true},
        {name: 'pays_id', type: 'int', useNull: true},
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
        {name: 'position_actuelle_fonction', type: 'string'},
        {name: 'position_actuelle_lieu', type: 'string'},
        {name: '_display', mapping: 0, convert: function(value, record) {
            return [
                record.get('prenom'),
                record.get('nom'),
                record.get('genre_nom_short')].join(' ');
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
        {name: 'date_these', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'lieu_these', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/candidats-formations',
    }
});
Ext.define('iafbm.model.Commission', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'termine', type: 'boolean', defaultValue: false},
        {name: 'nom', type: 'string'},
        {name: 'commentaire', type: 'string'},
        {name: 'commission-type_id', type: 'int'},
        {name: 'commission-type_nom', type: 'string'},
        {name: 'commission-type_racine', type: 'string'},
        {name: 'commission-etat_id', type: 'int', defaultValue: 1},
        {name: 'commission-etat_nom', type: 'string'},
        {name: 'commission-fonction_nom', type: 'string'},
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
        url: x.context.baseuri+'/api/commissions-etats',
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
        url: x.context.baseuri+'/api/commissions-types',
    }
});
Ext.define('iafbm.model.CommissionFonction', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'},
        {name: 'description', type: 'string'},
        {name: 'actif', type: 'boolean', defaultValue: true}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions-fonctions',
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
        url: x.context.baseuri+'/api/commissions-creations',
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
        url: x.context.baseuri+'/api/commissions-candidats-commentaires',
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
        url: x.context.baseuri+'/api/commissions-travails',
    }
});
Ext.define('iafbm.model.CommissionTravailEvenement', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'commission_id', type: 'int'},
        {name: 'commission-travail-evenement-type_id', type: 'int'},
        {name: 'date', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'proces_verbal', type: 'boolean'},
        {name: 'actif', type: 'boolean', defaultValue: true}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions-travails-evenements',
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
        url: x.context.baseuri+'/api/commissions-travails-evenements-types',
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
        url: x.context.baseuri+'/api/commissions-validations',
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
        url: x.context.baseuri+'/api/commissions-validations-etats',
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
        url: x.context.baseuri+'/api/commissions-finalisations',
    }
});
Ext.define('iafbm.model.Version', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'table_name', type: 'string'},
        {name: 'id_field_name', type: 'string'},
        {name: 'id_field_value', type: 'string'},
        {name: 'field_name', type: 'string'},
        {name: 'old_value', type: 'string'},
        {name: 'new_value', type: 'string'},
        {name: 'model_name', type: 'string'},
        {name: 'operation', type: 'string'}
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
        {name: 'version_modified', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'version_table_name', type: 'string'},
        {name: 'version_id_field_name', type: 'string'},
        {name: 'version_id_field_value', type: 'string'},
        {name: 'version_model_name', type: 'string'},
        {name: 'version_operation', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/versions-data',
    }
});

// Stores: creates one store per existing model
for (model in iafbm.model) {
    Ext.define(['iafbm', 'store', model].join('.'), {
        extend: 'Ext.ia.data.Store',
        model: ['iafbm', 'model', model].join('.')
    });
}