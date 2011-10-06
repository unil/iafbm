// Forms
Ext.ns('iafbm.form.common');
iafbm.form.common.Formations = function(options) {
    var config = {
        store: null,
        params: {}
    };
    var options = Ext.apply(config, options);
    return {
        xtype: 'fieldset',
        title: 'Formation supérieure',
        items: [{
            xtype: 'ia-editgrid',
            height: 150,
            bbar: null,
            newRecordValues: options.params,
            store: new options.store({
                params: options.params
            }),
            columns: [{
                header: "Formation",
                dataIndex: 'formation_id',
                width: 100,
                xtype: 'ia-combocolumn',
                field: {
                    xtype: 'ia-combo',
                    store: new iafbm.store.Formation(),
                    valueField: 'id',
                    displayField: 'abreviation',
                    allowBlank: false
                }
            },{
                header: "Lieu",
                dataIndex: 'lieu_these',
                flex: 1,
                editor: {
                    xtype: 'textfield',
                    allowBlank: false
                }
            },{
                header: "Date",
                dataIndex: 'date_these',
                flex: 1,
                xtype: 'ia-datecolumn',
                field: {
                    xtype: 'ia-datefield',
                    allowBlank: false
                }
            }]
        }]
    };
}
iafbm.form.common.Adresses = function(options) {
    var config = {
        store: null,
        params: {}
    };
    var options = Ext.apply(config, options);
    return {
        xtype: 'fieldset',
        title: 'Adresses',
        items: [{
            xtype: 'ia-editgrid',
            height: 150,
            bbar: null,
            newRecordValues: options.params,
            store: new options.store({
                params: options.params
            }),
            columns: [{
                header: "Type",
                dataIndex: 'adresse_adresse-type_id',
                width: 100,
                xtype: 'ia-combocolumn',
                field: {
                    xtype: 'ia-combo',
                    store: new iafbm.store.AdresseType(),
                    valueField: 'id',
                    displayField: 'nom',
                    allowBlank: false
                }
            },{
                header: "Adresse",
                dataIndex: 'adresse_rue',
                flex: 1,
                editor: {
                    xtype: 'textfield',
                    allowBlank: false
                }
            },{
                header: "NPA",
                dataIndex: 'adresse_npa',
                width: 75,
                editor: {
                    xtype: 'textfield',
                    allowBlank: false,
                    maskRe: /[0-9]/
                }
            },{
                header: "Lieu",
                dataIndex: 'adresse_lieu',
                flex: 1,
                editor: {
                    xtype: 'textfield',
                    allowBlank: false
                }
            },{
                header: "Pays",
                dataIndex: 'adresse_pays_id',
                width: 120,
                xtype: 'ia-combocolumn',
                field: {
                    xtype: 'ia-combo',
                    store: new iafbm.store.Pays(),
                    valueField: 'id',
                    displayField: 'nom',
                    allowBlank: false
                }
            }, {
                header: "Téléphone",
                dataIndex: 'adresse_telephone',
                width: 150,
                editor: {
                    xtype: 'textfield',
                    allowBlank: false,
                    maskRe: /[0-9]/
                }
            }]
        }]
    };
}
iafbm.form.common.Emails = function(options) {
    var config = {
        store: null,
        params: {}
    };
    var options = Ext.apply(config, options);
    return {
        xtype: 'fieldset',
        title: 'Emails',
        items: [{
            xtype: 'ia-editgrid',
            height: 150,
            bbar: null,
            newRecordValues: options.params,
            store: new options.store({
                params: options.params
            }),
            columns: [{
                header: "Type",
                dataIndex: 'adresse-type_id',
                width: 100,
                xtype: 'ia-combocolumn',
                field: {
                    xtype: 'ia-combo',
                    store: new iafbm.store.AdresseType(),
                    valueField: 'id',
                    displayField: 'nom',
                    allowBlank: false
                }
            },{
                header: "Email",
                dataIndex: 'email',
                flex: 1,
                editor: {
                    xtype: 'textfield',
                    allowBlank: false,
                    vtype: 'email'
                }
            }]
        }]
    };
}

Ext.define('iafbm.form.Candidat', {
    extend: 'Ext.ia.form.Panel',
    store: Ext.create('iafbm.store.Candidat'), //fixme, this should not be necessary
    title: 'Candidat',
    frame: true,
    fieldDefaults: {
        labelAlign: 'right',
        msgTarget: 'side'
    },
    defaults: {
        defaultType: 'textfield',
    },
    initComponent: function() {
        this.items = [
            this._createCandidats(),
        {
            xtype: 'fieldcontainer',
            layout: 'hbox',
            defaults: {
                flex: 1
            },
            items: [
                this._createFormations(),
            {
                xtype: 'splitter',
                flex: 0
            },
                this._createPositions()
            ]
        }, this._createAdresses()];
        //
        var me = this; me.callParent();
    },
    _createCandidats: function() {
        return {
            xtype: 'fieldset',
            title: 'Coordonnées',
            items: [{
                fieldLabel: 'Nom',
                emptyText: 'Nom',
                name: 'nom',
                allowBlank: false,
            }, {
                fieldLabel: 'Prénom',
                emptyText: 'Prénom',
                name: 'prenom',
                allowBlank: false,
            }, {
                xtype: 'ia-combo',
                fieldLabel: 'Genre',
                name: 'genre_id',
                displayField: 'genre',
                valueField: 'id',
                store: new iafbm.store.Genre({})
            }, {
                xtype: 'ia-datefield',
                fieldLabel: 'Date de naissance',
                emptyText: 'Date de naissance',
                name: 'date_naissance'
            }, {
                xtype: 'ia-combo',
                fieldLabel: 'Etat civil',
                name: 'etatcivil_id',
                displayField: 'nom',
                valueField: 'id',
                store: new iafbm.store.Etatcivil({})
            }, {
                xtype: 'numberfield',
                fieldLabel: 'Nombre d\'enfants',
                emptyText: 'Nombre d\'enfants',
                name: 'nombre_enfants',
                minValue: 0
            }, {
                fieldLabel: 'N° AVS',
                emptyText: 'N° AVS',
                name: 'no_avs',
                vtype: 'avs'
            }]
        }
    },
    _createFormations: function() {
        return iafbm.form.common.Formations({
            store: iafbm.store.CandidatFormation,
            params: {
                candidat_id: this.getRecordId()
            }
        });
    },
    _createPositions: function() {
        return {
            xtype: 'fieldset',
            title: 'Position actuelle',
            defaults: {
                border: false,
                flex: 1,
                msgTarget: 'side',
                labelAlign: 'right',
                labelWidth: 60,
                width: 300
            },
            defaultType: 'textfield',
            items: [{
                fieldLabel: 'Fonction',
                name: 'position_actuelle_fonction',
            },{
                fieldLabel: 'Lieu',
                name: 'position_actuelle_lieu'
            }]
        }
    },
    _createAdresses: function() {
        return {
            xtype: 'fieldset',
            title: 'Adresses',
            items: [{
                xtype: 'fieldcontainer',
                layout: 'hbox',
                defaults: {
                    fieldDefaults: {
                        labelAlign: 'right',
                        msgTarget: 'side'
                    },
                    border: false,
                    flex: 1,
                    defaultType: 'textfield'
                },
                items: [{
                    xtype: 'fieldcontainer',
                    items: [{
                        xtype: 'displayfield',
                        value: '<b>Professionnelle</b>',
                        labelSeparator: null, fieldLabel: '&nbsp;'
                    }, {
                        fieldLabel: 'Adresse',
                        emptyText: 'Adresse',
                        name: 'adresse_pro'
                    }, {
                        fieldLabel: 'NPA',
                        emptyText: 'NPA',
                        name: 'npa_pro'
                    }, {
                        fieldLabel: 'Lieu',
                        emptyText: 'Lieu',
                        name: 'lieu_pro'
                    }, {
                        xtype: 'ia-combo',
                        fieldLabel: 'Pays',
                        name: 'pays_pro_id',
                        displayField: 'nom',
                        valueField: 'id',
                        store: new iafbm.store.Pays({})
                    }, {
                        fieldLabel: 'Télépone',
                        emptyText: 'Télépone',
                        name: 'telephone_pro'
                    }, {
                        fieldLabel: 'Email',
                        emptyText: 'Email',
                        name: 'email_pro',
                        vtype: 'email'
                    }],
                }, {
                    xtype: 'fieldcontainer',
                    items: [{
                        xtype: 'displayfield',
                        value: '<b>Privée</b>',
                        labelSeparator: null, fieldLabel: '&nbsp;'
                    }, {
                        fieldLabel: 'Adresse',
                        emptyText: 'Adresse',
                        name: 'adresse_pri'
                    }, {
                        fieldLabel: 'NPA',
                        emptyText: 'NPA',
                        name: 'npa_pri'
                    }, {
                        fieldLabel: 'Lieu',
                        emptyText: 'Lieu',
                        name: 'lieu_pri'
                    }, {
                        xtype: 'ia-combo',
                        fieldLabel: 'Pays',
                        name: 'pays_pri_id',
                        displayField: 'nom',
                        valueField: 'id',
                        store: new iafbm.store.Pays({})
                    }, {
                        fieldLabel: 'Télépone',
                        emptyText: 'Télépone',
                        name: 'telephone_pri'
                    }, {
                        fieldLabel: 'Email',
                        emptyText: 'Email',
                        name: 'email_pri',
                        vtype: 'email'
                    }]
                }]
            }]
        }
    }
});

Ext.define('iafbm.form.Personne', {
    extend: 'Ext.ia.form.Panel',
    store: Ext.create('iafbm.store.Personne'), //fixme, this should not be necessary
    title:'Personne',
    frame: true,
    fieldDefaults: {
        labelAlign: 'right',
        msgTarget: 'side'
    },
    initComponent: function() {
        this.items = [{
            xtype: 'ia-combo',
            fieldLabel: 'Type',
            labelAlign: 'left',
            labelWidth: 40,
            name: 'personne-type_id',
            displayField: 'nom',
            valueField: 'id',
            store: Ext.create('iafbm.store.PersonneType')
        }, {
            xtype: 'fieldset',
            title: 'Coordonnées',
            defaultType: 'textfield',
            defaults: {
                labelWidth: 110
            },
            items: [{
                fieldLabel: 'Nom',
                emptyText: 'Nom',
                name: 'nom',
                allowBlank: false
            }, {
                fieldLabel: 'Prénom',
                emptyText: 'Prénom',
                name: 'prenom',
                allowBlank: false
            }, {
                xtype: 'ia-combo',
                fieldLabel: 'Genre',
                name: 'genre_id',
                displayField: 'genre',
                valueField: 'id',
                store: Ext.create('iafbm.store.Genre')
            }, {
                xtype: 'ia-datefield',
                fieldLabel: 'Date de naissance',
                name: 'date_naissance'
            }, {
                fieldLabel: 'N° AVS',
                emptyText: 'N° AVS',
                name: 'no_avs'
            }, {
                xtype: 'ia-combo',
                fieldLabel: 'Canton d\'origine',
                name: 'canton_id',
                displayField: 'nom',
                valueField: 'id',
                store: Ext.create('iafbm.store.Canton')
            }, {
                xtype: 'ia-combo',
                fieldLabel: 'Pays d\'origine',
                name: 'pays_id',
                displayField: 'nom',
                valueField: 'id',
                store: Ext.create('iafbm.store.Pays')
            }, {
                xtype: 'ia-combo',
                fieldLabel: 'Permis de séjour',
                name: 'permis_id',
                displayField: 'nom',
                valueField: 'id',
                store: Ext.create('iafbm.store.Permis')
            }]
        },
            this._createFormations(),
            this._createFonctions(),
            this._createAdresses(),
            this._createEmails()
        ];
        //
        var me = this;
        me.callParent();
    },
    _createFormations: function() {
        return iafbm.form.common.Formations({
            store: iafbm.store.PersonneFormation,
            params: {
                personne_id: this.getRecordId()
            }
        });
    },
    _createFonctions: function() {
        var personne_id = this.getRecordId();
        return {
            xtype: 'fieldset',
            title: 'Fonction académique',
            items: [{
                xtype: 'ia-editgrid',
                height: 150,
                bbar: null,
                newRecordValues: {
                    personne_id: personne_id
                },
                store: new iafbm.store.PersonneFonction({
                    params: { personne_id: personne_id }
                }),
                columns: [{
                    header: "Section",
                    dataIndex: 'section_id',
                    width: 60,
                    xtype: 'ia-combocolumn',
                    field: {
                        xtype: 'ia-combo',
                        store: new iafbm.store.Section(),
                        valueField: 'id',
                        displayField: 'code',
                        allowBlank: false
                    }
                },{
                    header: "Titre académique",
                    dataIndex: 'titre-academique_id',
                    flex: 1,
                    xtype: 'ia-combocolumn',
                    field: {
                        xtype: 'ia-combo',
                        store: new iafbm.store.TitreAcademique(),
                        valueField: 'id',
                        displayField: 'abreviation',
                        allowBlank: false
                    }
                },{
                    header: "Taux d'activité",
                    dataIndex: 'taux_activite',
                    width: 50,
                    xtype: 'numbercolumn',
                    format:'000',
                    field: {
                        xtype: 'numberfield',
                        maxValue: 100,
                        minValue: 0
                    }
                },{
                    header: "Date contrat",
                    dataIndex: 'date_contrat',
                    width: 100,
                    xtype: 'ia-datecolumn',
                    field: {
                        xtype: 'ia-datefield'
                    }
                },{
                    header: "Début mandat",
                    dataIndex: 'debut_mandat',
                    width: 100,
                    xtype: 'ia-datecolumn',
                    field: {
                        xtype: 'ia-datefield'
                    }
                },{
                    header: "Fonction hospitalière",
                    dataIndex: 'fonction-hospitaliere_id',
                    flex: 1,
                    xtype: 'ia-combocolumn',
                    field: {
                        xtype: 'ia-combo',
                        store: new iafbm.store.FonctionHospitaliere(),
                        valueField: 'id',
                        displayField: 'nom',
                        allowBlank: false
                    }
                },{
                    header: "Dépt (SSF) / Service (SSC)",
                    dataIndex: 'departement_id',
                    flex: 1,
                    xtype: 'ia-combocolumn',
                    field: {
                        xtype: 'ia-combo',
                        store: new iafbm.store.Departement(),
                        valueField: 'id',
                        displayField: 'nom',
                        allowBlank: false
                    }
                }]
            }]
        };
    },
    _createAdresses: function() {
        return iafbm.form.common.Adresses({
            store: iafbm.store.PersonneAdresse,
            params: {
                personne_id: this.getRecordId()
            }
        });
    },
    _createEmails: function() {
        return iafbm.form.common.Emails({
            store: iafbm.store.PersonneEmail,
            params: {
                personne_id: this.getRecordId()
            }
        });
    }
});