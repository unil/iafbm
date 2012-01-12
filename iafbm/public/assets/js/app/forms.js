// Forms
Ext.ns('iafbm.form');

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
        this.items = [{
            xtype: 'ia-combo-version',
            tables: ['candidats', 'candidats_formations']
        },
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
        return {
            xtype: 'fieldset',
            title: 'Diplômes obtenus',
            items: [
                new iafbm.grid.common.Formations({
                    store: new iafbm.store.CandidatFormation({
                        params: { candidat_id: this.getRecordId() },
                    }),
                    newRecordValues: { candidat_id: this.getRecordId() },
                    listeners: {afterrender: function() {
                        // Disables grid if the contrainer form record is phantom
                        // (e.g does not exist in database yet)
                        var record = this.up('form').record;
                        if (!record) return;
                        var id = record.get('id'),
                            phantom = !Boolean(id);
                        if (phantom) this.disable();
                        // Enables grid after record is saved
                        var me = this;
                        this.up('form').on('aftersave', function() {
                            me.enable();
                            // (re)Sets store params and newRecordsParams
                            // because they might be undefined
                            // if the container form was loaded without a record
                            me.store.params = me.newRecordValues = {
                                candidat_id: this.getRecordId()
                            };
                        });
                    }}
                })
            ]
        };
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
                    }, {
                        xtype: 'radio',
                        fieldLabel: 'Par défaut',
                        name: 'adresse_defaut',
                        inputValue: 'pro'
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
                    }, {
                        xtype: 'radio',
                        fieldLabel: 'Par défaut',
                        name: 'adresse_defaut',
                        inputValue: 'pri'
                    }]
                }]
            }]
        }
    }
});

Ext.define('iafbm.form.Personne', {
    extend: 'Ext.ia.form.Panel',
    store: Ext.create('iafbm.store.Personne'), // FIXME: this should not be necessary
    title:'Personne',
    frame: true,
    initComponent: function() {
        this.items = [{
            xtype: 'fieldcontainer',
            items: [{
                xtype: 'ia-combo-version',
                tables: ['personnes', 'adresses', 'personnes_adresses', 'personnes_telephones', 'personnes_emails', 'personnes_formations', 'personnes_activites', 'commissions_membres']
            }]
        }, {
            xtype: 'fieldcontainer',
            layout: 'hbox',
            items: [{
                xtype: 'fieldcontainer',
                width: 330,
                items: [
                    this._createType(),
                    this._createCoordonnees(),
                ],
            }, {
                xtype: 'splitter',
                flex: 0
            }, {
                xtype: 'fieldcontainer',
                flex: 1,
                items: [
                    this._createAdresses(),
                    this._createTelephones(),
                    this._createEmails()
                ]
            }]
        },
            this._createFormations(),
        {
            xtype: 'fieldset',
            title: 'Carrière professionnelle UNIL-CHUV',
            items: [
                { html: 'SSF' },
                this._createActivites(2),
                { html: 'SSC', padding: '20 0 0 0' },
                this._createActivites(1)
            ]
        },
            this._createCommissionsCurrent()
        ];
        //
        var me = this;
        me.callParent();
    },
    switchType: function() {
        var type = this.getValue();
        this.up('panel').cascade(function(c) {
            if (!c.iaDisableFor) return;
            var disabled = type==null || Ext.Array.contains(c.iaDisableFor, type);
            c.setDisabled(disabled);
        });
    },
    _createType: function() {
        return {
            xtype: 'fieldset',
            title: 'Type de personne',
            items: [{
                xtype: 'ia-combo',
                fieldLabel: 'Type',
                labelAlign: 'left',
                labelWidth: 110,
                width: 300,
                name: 'personne_type_id',
                displayField: 'nom',
                valueField: 'id',
                store: Ext.create('iafbm.store.PersonneType'),
                allowBlank: false,
                typeAhead: false,
                editable: false,
                listeners: {
                    change: this.switchType
                }
            }]
        };
    },
    _createCoordonnees: function() {
        return {
            xtype: 'fieldset',
            title: 'Coordonnées',
            height: 417,
            defaultType: 'textfield',
            defaults: {
                labelWidth: 110,
                width: 300,
                padding: '10 0',
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
                store: Ext.create('iafbm.store.Genre'),
                iaDisableFor: []
            }, {
                xtype: 'ia-datefield',
                fieldLabel: 'Date de naissance',
                name: 'date_naissance',
                iaDisableFor: []
            }, {
                fieldLabel: 'N° AVS',
                emptyText: 'N° AVS',
                name: 'no_avs',
                vtype: 'avs',
                iaDisableFor: []
            }, {
                xtype: 'ia-combo',
                fieldLabel: 'Canton d\'origine',
                name: 'canton_id',
                displayField: 'nom',
                valueField: 'id',
                store: Ext.create('iafbm.store.Canton'),
                iaDisableFor: []
            }, {
                xtype: 'ia-combo',
                fieldLabel: 'Pays d\'origine',
                name: 'pays_id',
                displayField: 'nom',
                valueField: 'id',
                store: Ext.create('iafbm.store.Pays'),
                iaDisableFor: []
            }, {
                xtype: 'ia-combo',
                fieldLabel: 'Permis de séjour',
                name: 'permis_id',
                displayField: 'nom',
                valueField: 'id',
                store: Ext.create('iafbm.store.Permis'),
                iaDisableFor: []
            }]
        };
    },
    _createAdresses: function() {
        return {
            xtype: 'fieldset',
            title: 'Adresses',
            items: [
                new iafbm.grid.common.Adresses({
                    store: new iafbm.store.PersonneAdresse({
                        params: { personne_id: this.getRecordId() },
                    }),
                    newRecordValues: { personne_id: this.getRecordId() },
                    iaDisableFor: []
                })
            ]
        };
    },
    _createTelephones: function() {
        return {
            xtype: 'fieldset',
            title: 'Telephones',
            items: [
                new iafbm.grid.common.Telephones({
                    store: new iafbm.store.PersonneTelephone({
                        params: { personne_id: this.getRecordId() },
                    }),
                    newRecordValues: { personne_id: this.getRecordId() },
                    iaDisableFor: []
                })
            ]
        };
    },
    _createEmails: function() {
        return {
            xtype: 'fieldset',
            title: 'Emails',
            items: [
                new iafbm.grid.common.Emails({
                    store: new iafbm.store.PersonneEmail({
                        params: { personne_id: this.getRecordId() },
                    }),
                    newRecordValues: { personne_id: this.getRecordId() },
                    iaDisableFor: []
                })
            ]
        };
    },
    _createFormations: function() {
        return {
            xtype: 'fieldset',
            title: 'Diplômes obtenus',
            items: [
                new iafbm.grid.common.Formations({
                    store: new iafbm.store.PersonneFormation({
                        params: { personne_id: this.getRecordId() },
                    }),
                    newRecordValues: { personne_id: this.getRecordId() },
                    iaDisableFor: [2,3]
                })
            ]
        };
    },
    _createActivites: function(section_id) {
        if (typeof section_id == 'undefined') throw "Missing section_id parameter";
        var personne_id = this.getRecordId();
        return {
            xtype: 'ia-editgrid',
            height: 150,
            toolbarButtons: ['add', 'delete'],
            toolbarLabels: {
                add: 'Ajouter un contrat',
                delete: 'Supprimer le contrat'
            },
            bbar: null,
            newRecordValues: {
                personne_id: personne_id
            },
            store: new iafbm.store.PersonneActivite({
                params: {
                    personne_id: personne_id,
                    activite_section_id: section_id
                }
            }),
            iaDisableFor: [2,3],
            columns: [{
                // This column is used as a filter for 'Activite' field
                header: "Type",
                dataIndex: 'activite_activite_type_id',
                width: 100,
                xtype: 'ia-combocolumn',
                editor: {
                    xtype: 'ia-combo',
                    store: new iafbm.store.ActiviteType(),
                    valueField: 'id',
                    displayField: 'nom',
                    allowBlank: false
                }
            },{
                header: "Activité",
                dataIndex: 'activite_id',
                flex: 1,
                xtype: 'ia-combocolumn',
                editor: {
                    xtype: 'ia-combo',
                    store: new iafbm.store.Activite({
                        params: { section_id: section_id }
                    }),
                    valueField: 'id',
                    displayField: 'abreviation',
                    allowBlank: false,
                    // Manages list filtering: only shows titres-academiques related to the member
                    queryMode: 'local',
                    listeners: {
                        afterrender: function() {
                            this.prev().on('select', this.clearValue, this);
                        },
                        beforequery: function(queryEvent, eventOpts) {
                            // Filters store records, keeping only the titles related the 'activite type'
                            var activite_type_id = this.prev().getValue();
                            this.store.clearFilter();
                            this.store.filter('activite_type_id', activite_type_id);
                            queryEvent.cancel = true;
                            this.expand();
                        },
                        collapse: function(combo, record, index) {
                            this.store.clearFilter();
                        }
                    }
                }
            },{
                header: "Département",
                dataIndex: 'departement_id',
                flex: 1,
                xtype: 'ia-combocolumn',
                editor: {
                    xtype: 'ia-combo',
                    store: new iafbm.store.Departement({
                        params: { section_id: section_id }
                    }),
                    valueField: 'id',
                    displayField: 'nom',
                    allowBlank: false
                }
            },{
                header: "% Taux d'activité",
                dataIndex: 'taux_activite',
                width: 47,
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
            }]
        };
    },
    _createCommissionsCurrent: function() {
        var personne_id = this.getRecordId();
        // Adds specific column
        var store = new iafbm.store.CommissionMembre({
            params: {
                personne_id: personne_id,
                xjoin: 'commission,commission_fonction,section,commission_etat,commission_type',
                xorder_by: 'commission_etat_id',
                xorder: 'ASC'
            }
        });
        return {
            xtype: 'fieldset',
            title: 'Participation aux commissions',
            items: [{
                xtype: 'ia-editgrid',
                editable: false,
                toolbarButtons: ['search'],
                height: 150,
                bbar: null,
                store: store,
                searchParams: { xwhere: 'query' },
                iaDisableFor: [],
                columns: [{
                    xtype: 'ia-actioncolumn-redirect',
                    width: 25,
                    text: 'Détails commission',
                    tooltip: 'Détails commission',
                    getLocation: function(grid, record, id) {
                        return [
                            x.context.baseuri,
                            'commissions',
                            record.get('commission_id')
                        ].join('/');
                    }
                },{
                    header: "Fonction occupée",
                    dataIndex: 'commission_fonction_nom',
                    width: 210
                },{
                    header: "Type",
                    dataIndex: 'commission_type_racine',
                    width: 100
                },{
                    header: "N°",
                    dataIndex: 'commission_id',
                    width: 35
                },{
                    header: "Nom",
                    dataIndex: 'commission_nom',
                    flex: 1
                },{
                    header: "Section",
                    dataIndex: 'section_code',
                    width: 50
                },{
                    header: "Etat",
                    dataIndex: 'commission_etat_nom',
                    width: 75
                }]
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
