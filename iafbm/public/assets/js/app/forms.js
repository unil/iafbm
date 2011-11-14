// Forms
Ext.ns('iafbm.form.common');
iafbm.form.common.Formations = function(options) {
    var config = {
        store: null,
        params: {},
        searchParams: {}
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
            searchParams: options.searchParams,
            columns: [{
                header: "Formation",
                dataIndex: 'formation_id',
                width: 100,
                xtype: 'ia-combocolumn',
                editor: {
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
                    xtype: 'textfield'
                }
            },{
                header: "Date",
                dataIndex: 'date_these',
                flex: 1,
                xtype: 'ia-datecolumn',
                editor: {
                    xtype: 'ia-datefield'
                }
            },{
                header: "Commentaire",
                dataIndex: 'commentaire',
                flex: 1,
                editor: {
                    xtype: 'textfield'
                }
            }]
        }]
    };
}
iafbm.form.common.Adresses = function(options) {
    var config = {
        store: null,
        params: {},
        searchParams: {}
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
            searchParams: options.searchParams,
            columns: [{
                header: "Type",
                dataIndex: 'adresse_adresse-type_id',
                width: 100,
                xtype: 'ia-combocolumn',
                editor: {
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
                renderer: function(value) {
                    // Converts NL|CR into <br/> for field display
                    var breakTag = '<br/>';
                    value = (value + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
                    return ['<div style="white-space:normal">', value, '</div>'].join('');
                },
                editor: {
                    //xtype: 'textfield',
                    xtype: 'ia-textarea',
                    grow: true,
                    growMin: 22,
                    growMax: 22,
                    fixEditorHeight: function() {
                        // FIXME: Not used, should be deleted after feature validation
                        // Sets RowEditor panel height according textarea height
                        var editorHeight = this.el.down('textarea').getHeight();
                        this.up('panel').setHeight(editorHeight+20);
                    },
                    fireKey: function(event) {
                        // Accepts ENTER as regular key
                        if (event.getKey() == event.ENTER) event.stopPropagation();
                        //this.fixEditorHeight();
                    },
                    //listeners: {focus: function() {
                    //    this.fixEditorHeight();
                    //}}
                }
            },{
                header: "NPA",
                dataIndex: 'adresse_npa',
                width: 60,
                editor: {
                    xtype: 'textfield',
                    maskRe: /[0-9]/
                }
            },{
                header: "Lieu",
                dataIndex: 'adresse_lieu',
                width: 150,
                editor: {
                    xtype: 'textfield',
                }
            },{
                header: "Pays",
                dataIndex: 'adresse_pays_id',
                width: 120,
                xtype: 'ia-combocolumn',
                editor: {
                    xtype: 'ia-combo',
                    store: new iafbm.store.Pays(),
                    valueField: 'id',
                    displayField: 'nom',
                }
            },{
                header: "Indicatif pays",
                dataIndex: 'adresse_telephone_countrycode',
                xtype: 'templatecolumn',
                tpl: '<tpl if="adresse_telephone_countrycode.length &gt; 0">+</tpl>{adresse_telephone_countrycode}',
                width: 30,
                editor: {
                    xtype: 'textfield',
                    maxLength: 3,
                    enforceMaxLength: true,
                    maskRe: /[0-9]/,
                    vtype: 'telcc',
                    validator: function(value) {
                        var telephone = this.nextSibling();
                        return (telephone.getValue().length && !this.getValue().length) ?
                            'Entrez l\'indicatif pays (p.ex. 41 pour la suisse)' : true;
                    }
                }
            },{
                header: "Téléphone",
                dataIndex: 'adresse_telephone',
                width: 85,
                editor: {
                    xtype: 'textfield',
                    maskRe: /[0-9]/,
                    validator: function(value) {
                        var indicatif = this.previousSibling();
                        return (indicatif.getValue().length && !this.getValue().length) ?
                            'Entrez un numéro de téléphone après l\'indicatif pays' : true;
                    }
                }
            },{
                header: "Par défaut",
                dataIndex: 'defaut',
                width: 65,
                xtype: 'ia-radiocolumn',
                editor: {
                    xtype: 'checkboxfield',
                    disabled: true
                }
            }]
        }]
    };
}
iafbm.form.common.Emails = function(options) {
    var config = {
        store: null,
        params: {},
        searchParams: {}
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
            searchParams: options.searchParams,
            columns: [{
                header: "Type",
                dataIndex: 'adresse-type_id',
                width: 100,
                xtype: 'ia-combocolumn',
                editor: {
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
            },{
                header: "Par défaut",
                dataIndex: 'defaut',
                width: 65,
                xtype: 'ia-radiocolumn',
                editor: {
                    xtype: 'checkbox',
                    disabled: true
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
        labelWidth: 110
    },
    defaults: {
        defaultType: 'textfield',
    },
    initComponent: function() {
        this.items = [
            this._createCandidat(),
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
    _createCandidat: function() {
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
                xtype: 'ia-combo',
                fieldLabel: 'Pays d\'origine',
                name: 'pays_id',
                displayField: 'nom',
                valueField: 'id',
                store: new iafbm.store.Pays({})
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
        // Common telephone fieldcontainer for both
        // professional and private telephone numbers
        var _createTelephone = function(prefix) {
            return {
                xtype: 'fieldcontainer',
                layout: 'hbox',
                width: 255,
                fieldLabel: 'Télépone',
                defaultType: 'textfield',
                fieldDefaults: {
                    msgTarget: 'side'
                },
                items: [{
                    xtype: 'displayfield',
                    value: '+',
                }, {
                    name: ['telephone', prefix, 'countrycode'].join('_'),
                    width: 40,
                    margins: '0 5 0 0',
                    maxLength: 3,
                    enforceMaxLength: true,
                    maskRe: /[0-9]/,
                    vtype: 'telcc',
                    validator: function(value) {
                        var telephone = this.nextSibling();
                        return (telephone.getValue().length && !this.getValue().length) ?
                            'Entrez l\'indicatif pays (p.ex 41 pour la suisse)' : true;
                    }
                }, {
                    name: ['telephone', prefix].join('_'),
                    emptyText: 'Télépone',
                    flex: 1,
                    maskRe: /[0-9]/,
                    validator: function(value) {
                        var indicatif = this.previousSibling();
                        return (indicatif.getValue().length && !this.getValue().length) ?
                            'Entrez un numéro de téléphone après l\'indicatif pays' : true;
                    }
                }]
            }
        }
        // Actual fieldset config for adresses
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
                        xtype: 'ia-textarea',
                        grow: true,
                        growMin: 0,
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
                    },
                        _createTelephone('pro'),
                    {
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
                        xtype: 'ia-textarea',
                        grow: true,
                        growMin: 0,
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
                    },
                        _createTelephone('pri'),
                    {
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
            this._createEmails(),
            this._createCommissionsCurrent(),
            this._createCommissionsClosed()
        ];
        //
        var me = this;
        me.callParent();
    },
    _createFormations: function() {
        return iafbm.form.common.Formations({
            store: iafbm.store.PersonneFormation,
            params: { personne_id: this.getRecordId() }
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
                    editor: {
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
                    editor: {
                        xtype: 'ia-combo',
                        store: new iafbm.store.TitreAcademique(),
                        valueField: 'id',
                        displayField: 'abreviation',
                        allowBlank: false
                    }
                },{
                    header: "% Taux d'activité",
                    dataIndex: 'taux_activite',
                    width: 48,
                    align: 'right',
                    xtype: 'numbercolumn',
                    format: '000',
                    xtype: 'templatecolumn',
                    tpl: '{taux_activite}<tpl if="taux_activite!=null">%</tpl>',
                    editor: {
                        xtype: 'numberfield',
                        maxValue: 100,
                        minValue: 0
                    }
                },{
                    header: "Date contrat",
                    dataIndex: 'date_contrat',
                    width: 100,
                    xtype: 'ia-datecolumn',
                    editor: {
                        xtype: 'ia-datefield'
                    }
                },{
                    header: "Début mandat",
                    dataIndex: 'debut_mandat',
                    width: 100,
                    xtype: 'ia-datecolumn',
                    editor: {
                        xtype: 'ia-datefield',
                        validator: function(value) {
                            var debut = this.getValue(),
                                fin = this.nextSibling().getValue();
                            if (debut && !fin)
                                return true;
                            if (!debut && fin)
                                return 'Si le mandat a une fin, la date de début doit être spécifiée';
                            if (debut>fin)
                                return 'La date de début de mandat doit être antérieure à la date de fin';
                            return true;
                        }
                    }
                },{
                    header: "Fin mandat",
                    dataIndex: 'fin_mandat',
                    width: 100,
                    xtype: 'ia-datecolumn',
                    editor: {
                        xtype: 'ia-datefield',
                        validator: function(value) {
                            var debut = this.previousSibling().getValue(),
                                fin = this.getValue();
                            if (debut && !fin)
                                return true;
                            if (debut>fin)
                                return 'La fin de mandat doit être utlérieure au début de mandat';
                            return true;
                        }
                    }
                },{
                    header: "Fonction hospitalière",
                    dataIndex: 'fonction-hospitaliere_id',
                    flex: 1,
                    xtype: 'ia-combocolumn',
                    editor: {
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
                    editor: {
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
            params: { personne_id: this.getRecordId() }
        });
    },
    _createEmails: function() {
        return iafbm.form.common.Emails({
            store: iafbm.store.PersonneEmail,
            params: { personne_id: this.getRecordId() }
        });
    },
    _createCommissionsCurrent: function() {
        var personne_id = this.getRecordId();
        // Adds specific column
        var store = new iafbm.store.Commission({
            params: {
                xjoin: 'commission-membre,commission-fonction',
                'commission-membre_personne_id': personne_id
            }
        });
        var columns = Ext.Array.clone(iafbm.columns.Commission);
        columns.splice(1, 0, {
            header: "Fonction",
            dataIndex: 'commission-fonction_nom',
            width: 200
        });
        return {
            xtype: 'fieldset',
            title: 'Commissions courantes',
            items: [{
                xtype: 'ia-editgrid',
                editable: false,
                toolbarButtons: ['search'],
                height: 150,
                bbar: null,
                store: store,
                searchParams: { xwhere: 'query' },
                columns: columns
            }]
        }
    },
    _createCommissionsClosed: function() {
        // TODO
        return {
            xtype: 'fieldset',
            title: 'Commissions archivées',
            items: [{
                html: 'Cette fonctionnalité est en cours de développement',
            }]
        }
    }
});

Ext.define('iafbm.form.PropositionNomination', {
    extend: 'Ext.ia.form.Panel',
    //store: Ext.create('iafbm.store.Candidat'), //fixme, this should not be necessary
    title: 'Proposition de nomination',
    frame: true,
    initComponent: function() {
        this.items = [{
            xtype: 'fieldset',
            title: 'Proposition de nomination',
            items: []
        }, {
            xtype: 'fieldset',
            title: 'Coordonnées',
            items: []
        }, {
            xtype: 'fieldset',
            title: 'Annexes',
            items: []
        }, {
            xtype: 'fieldset',
            title: 'Imputation',
            items: []
        }]
        var me = this;
        me.callParent();
    }
});
