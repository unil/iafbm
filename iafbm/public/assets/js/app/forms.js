// Forms
Ext.ns('iafbm.form');

Ext.define('iafbm.form.Candidat', {
    extend: 'Ext.ia.form.Panel',
    store: Ext.create('iafbm.store.Candidat'), // FIXME: this should not be necessary
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
            xtype: 'ia-versioning',
            comboConfig: {
                modelname: 'candidat',
                modelid: this.getRecordId()
            }
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
                displayField: 'nom',
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
            height: 155,
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
            height: 155,
            padding: '20 0 0 0',
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
                    height: 250,
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
                        growMax: 65,
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
                        growMax: 65,
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
            xtype: 'ia-versioning',
            comboConfig: {
                modelname: 'personne',
                modelid: this.getRecordId()
            }
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
            height: 407,
            defaultType: 'textfield',
            defaults: {
                labelWidth: 110,
                width: 300,
                padding: '5 0',
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
                displayField: 'nom',
                valueField: 'id',
                store: Ext.create('iafbm.store.Genre'),
                iaDisableFor: []
            }, {
                xtype: 'ia-combo',
                fieldLabel: 'Dénomination',
                name: 'personne_denomination_id',
                displayField: 'nom',
                valueField: 'id',
                store: Ext.create('iafbm.store.PersonneDenomination'),
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
                fieldLabel: 'Etat civil',
                name: 'etatcivil_id',
                displayField: 'nom',
                valueField: 'id',
                store: Ext.create('iafbm.store.Etatcivil')
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
            title: 'Téléphones',
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
                dataIndex: 'activite_type_id',
                width: 100,
                xtype: 'ia-combocolumn',
                editor: {
                    xtype: 'ia-combo',
                    // 'activite' store is used to list 'activite_type' items
                    // that belong to section_id and contain at least one 'activite'
                    store: new iafbm.store.Activite({
                        params: {
                            section_id: section_id,
                            xgroup_by: 'activite_type_id',
                            xreturn: [
                                'id',
                                'activite_type_id',
                                'activite_type_nom',
                            ].join()
                        }
                    }),
                    valueField: 'activite_type_id',
                    displayField: 'activite_type_nom',
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
                    displayField: 'activite_nom_abreviation',
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
                header: (section_id==1) ? "Service" : "Département",
                dataIndex: 'rattachement_id',
                flex: 1,
                xtype: 'ia-combocolumn',
                editor: {
                    xtype: 'ia-combo',
                    store: new iafbm.store.Rattachement({
                        params: { section_id: section_id }
                    }),
                    valueField: 'id',
                    displayField: 'nom',
                    allowBlank: false
                }
            },{
                header: "Taux (%)",
                dataIndex: 'taux_activite',
                width: 75,
                align: 'right',
                xtype: 'ia-percentcolumn',
                editor: {
                    xtype: 'ia-percentfield',
                    maxValue: 100,
                    minValue: 0
                }
            },{
                header: "Début",
                dataIndex: 'debut',
                width: 85,
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
                header: "Fin",
                dataIndex: 'fin',
                width: 85,
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
                // TODO: FIXME: Default sort should be specified on the ExtJS column definition
                xorder_by: 'commission_etat_id',
                xorder: 'ASC'
            }
        });
        return {
            xtype: 'fieldset',
            title: 'Participation à des commissions',
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

Ext.define('iafbm.form.CommissionPropositionNomination', {
    extend: 'Ext.ia.form.Panel',
    store: Ext.create('iafbm.store.CommissionPropositionNomination'), // FIXME: this should not be necessary
    // Common stores used by multiple widgets within this form.
    common: {
        // This store is used to load the user selected 'candidat'
        // for information display
        store_candidat: new iafbm.store.Candidat({
            autoLoad: false
        })
    },
    title: 'Proposition de nomination',
    frame: true,
    // Applies the received record fields to forms fields, according mapping.
    // mapping structure: { form_field_name: 'record_field_name' }
    applyToForm: function(record, mapping) {
        var form = this,
            record = record || null,
            mapping = mapping || {};
        // Workaround:
        // Creates a hashmap with the form fields that we want to manipulate
        var keys = [];
        Ext.iterate(mapping, function(key) {
            keys.push(key);
        });
        var fields = {};
        form.cascade(function(item) {
            if (Ext.Array.contains(keys, item.name)) fields[item.name] = item;
        });
        Ext.iterate(fields, function(name, field) {
            var candidat_field = mapping[name];
            field.setValue(record.get(candidat_field));
        });
    },
    //
    initComponent: function() {
        var me = this;
        if (!this.fetch.params.commission_id) throw new Error("commission_id property cannot be empty");
        // Ad-hoc 'commission' store setup (for info fields values)
        new iafbm.store.Commission().load({
            params: { id: this.fetch.params.commission_id },
            callback: function(records, operation, success) {
                if (!success) return;
                me.applyToForm(records.pop(), {
                    __commission__section_id: 'section_id'
                });
            }
        });
        // Ad-hoc 'candidat' store setup (see this.common.store_commission)
        this.common.store_candidat.on('load', function(store, records, success) {
            if (!success) return;
            me.applyToForm(records.pop(), {
                __candidat_denomination_id: 'denomination_id',
                __candidat_nom: 'nom',
                __candidat_prenom: 'prenom',
                __candidat_pays_id: 'pays_id',
                __candidat_etatcivil_id: 'etatcivil_id',
                __candidat_permis_id: 'permis_id',
                __candidat_date_naissance: 'date_naissance',
                __candidat_defaut_adresse: '_adresse_defaut',
                __candidat_defaut_npa: '_npa_defaut',
                __candidat_defaut_lieu: '_lieu_defaut',
                __candidat_defaut_pays_id: '_pays_defaut_id',
                __candidat_defaut_email: '_email_defaut',
                __candidat_position_actuelle_fonction: 'position_actuelle_fonction'
            });
        });
        // Form defaults
        this.fieldDefaults = {
            width: 400
        },
        // Form items
        this.items = [{
            xtype: 'fieldcontainer',
            layout: 'hbox',
            width: '100%',
            items: [{
                xtype: 'ia-combo',
                fieldLabel: 'Candidat',
                name: 'candidat_id',
                displayField: '_display', // TODO: Use template instead?
                valueField: 'id',
                store: new iafbm.store.Candidat({
                    params: { commission_id: this.fetch.params.commission_id }
                }),
                editable: false,
                width: 400,
                labelWidth: 65,
                // Reloads store_candidat with selected 'candidat' data
                // Sets 'nomination' model fields according 'candidat' fields values
                listeners: { change: function() {
                    var candidat_id = this.getValue(),
                        store_candidat = this.up('form').common.store_candidat;
                    if (!candidat_id) return;
                    store_candidat.load({params:{id:candidat_id}});
                }}
            }, {
                xtype: 'button',
                text: 'Editer le candidat',
                iconCls: 'icon-edit',
                margin: '0 5',
                handler: function() {
                    var candidat_id = this.prev().getValue(),
                        common = this.up('form').common,
                        popup = new Ext.ia.window.Popup({
                        title: 'Candidat',
                        item: new iafbm.form.Candidat({
                            frame: false,
                            modal: true,
                            fetch: {
                                model: iafbm.model.Candidat,
                                id: candidat_id
                            }
                        }),
                        listeners: {
                            // Reloads candidat_store record on close (for refreshing data in PropositionNomination fields)
                            close: function() {
                                common.store_candidat.load();
                                // TODO: Reload combo.store too, or make combo use the common.store_candidat
                            }
                        }
                    });
                },
                listeners: {
                    // Listens to candidat-combo to disable button when no candidat selected
                    afterrender: function() {
                        var button = this,
                            combo = this.prev();
                        combo.on({change: function(combo, value) {
                            button.setDisabled(!value);
                        }});
                        // Fires 'change' envent to trigger button en/disabled state
                        combo.fireEvent('change', combo, combo.getValue(), undefined);
                    }
                }
            }],
        }, {
            xtype: 'fieldset',
            title: 'Proposition de nomination',
            items: [{
                xtype: 'textfield',
                readOnly: true,
                fieldLabel: 'Faculté',
                value: 'Faculté de biologie et de médecine',
            }, {
                xtype: 'ia-combo',
                readOnly: true,
                fieldLabel: 'Section',
                name: '__commission__section_id',
                displayField: 'code',
                valueField: 'id',
                store: Ext.create('iafbm.store.Section')
            }, {
                //FIXME
                xtype: 'displayfield',
                fieldLabel: 'Institut',
                value: '?'
            }, {
                xtype: 'textfield',
                fieldLabel: 'Objet'
            }, {
                //FIXME
                xtype: 'displayfield',
                fieldLabel: 'Titre proposé',
                value: '?'
            }, {
                xtype: 'fieldcontainer',
                layout: 'hbox',
                fieldLabel: 'Début du contrat',
                items: [{
                    xtype: 'ia-datefield',
                }, {
                    xtype: 'displayfield',
                    value: '&nbsp;'
                }, {
                    xtype: 'checkbox',
                    boxLabel: 'Au plutot tôt',
                    handler: function() {
                        var datefield = this.up().down('datefield');
                        datefield.setDisabled(this.checked);
                        this.checked ? datefield.hide() : datefield.show();
                    }
                }]
            }, {
                xtype: 'ia-datefield',
                fieldLabel: 'Fin du contrat'
            }, {
                xtype: 'ia-percentfield',
                fieldLabel: "Taux d'activité",
                name: 'contrat_taux'
            }, {
                fieldLabel: 'Charge horaire',
                name: ''
            }, {
                xtype: 'numberfield',
                fieldLabel: 'Indemnité (CHF)'
            }, {
                xtype: 'ia-combo',
                readOnly: true,
                fieldLabel: 'Primo loco',
                editable: false,
                valueField: 'value',
                displayField: 'label',
                store: new Ext.data.ArrayStore({
                    fields: ['value', 'label'],
                    data: [[1, 'Oui'], [0, 'Non']]
                }),
            }, {
                xtype: 'displayfield',
                fieldLabel: 'Autres candidats',
                value: 'todo'
            }]
        }, {
            xtype: 'fieldset',
            title: 'Coordonnées',
            items: [{
                xtype: 'ia-combo',
                readOnly: true,
                fieldLabel: 'Dénomination',
                name: '__candidat_denomination_id',
                displayField: 'abreviation',
                valueField: 'id',
                store: Ext.create('iafbm.store.PersonneDenomination'),
            }, {
                readOnly: true,
                fieldLabel: 'Nom',
                name: '__candidat_nom'
            }, {
                readOnly: true,
                fieldLabel: 'Prénom',
                name: '__candidat_prenom'
            }, {
                readOnly: true,
                fieldLabel: 'Adresse',
                name: '__candidat_defaut_adresse'
            }, {
                readOnly: true,
                fieldLabel: 'NPA',
                name: '__candidat_defaut_npa',
            }, {
                readOnly: true,
                fieldLabel: 'Lieu',
                name: '__candidat_defaut_lieu',
            }, {
                readOnly: true,
                fieldLabel: 'Email',
                name: '__candidat_defaut_email',
            }, {
                xtype: 'ia-combo',
                readOnly: true,
                fieldLabel: 'Etat civil',
                name: '__candidat_etatcivil_id',
                displayField: 'nom',
                valueField: 'id',
                store: Ext.create('iafbm.store.Etatcivil')
            }, {
                xtype: 'ia-datefield',
                readOnly: true,
                fieldLabel: 'Date de naissance',
                name: '__candidat_date_naissance'
            }, {
                xtype: 'ia-combo',
                readOnly: true,
                fieldLabel: "Pays d'origine",
                name: '__candidat_pays_id',
                displayField: 'nom',
                valueField: 'id',
                store: Ext.create('iafbm.store.Pays')
            }, {
                xtype: 'ia-combo',
                readOnly: true,
                fieldLabel: "Canton d'origine",
                name: null, //FIXME
                displayField: 'nom',
                valueField: 'id',
                store: Ext.create('iafbm.store.Canton')
            }, {
                xtype: 'ia-combo',
                readOnly: true,
                fieldLabel: 'Permis',
                name: '__candidat_permis_id',
                displayField: 'nom',
                valueField: 'id',
                store: Ext.create('iafbm.store.Permis')
            }, {
                // Data: candidat.fonction_actuelle
                readOnly: true,
                fieldLabel: 'Fonction actuelle',
                name: '__candidat_position_actuelle_fonction'
            }, {
                fieldLabel: 'Discipline générale',
                name: null //FIXME
            }, {
                xtype: 'ia-combo',
                fieldLabel: 'Grade universitaire',
                name: null, //FIXME
                displayField: 'abreviation',
                valueField: 'id',
                store: Ext.create('iafbm.store.Formation')
            }, {
                xtype: 'ia-datefield',
                fieldLabel: "Lieu et date de l'obtention du grade",
                name: null //FIXME
            }, {
                // Data: commission_validation: Décanat or CF? ask.
                xtype: 'ia-datefield',
                fieldLabel: 'Date préavis',
                name: null //FIXME
            }, {
                xtype: 'ia-textarea',
                fieldLabel: 'Observations',
                name: null, //FIXME
                grow: true
            }]
        }, {
            xtype: 'fieldset',
            title: 'Annexes',
            // Shows/hides box label on select/unselect
            defaults: {
                listeners: {
                    afterrender: function() { this.handler() },
                    change: function() { this.handler() }
                },
                handler: function() {
                    var el = this.boxLabelEl;
                    this.checked ? el.show() : el.hide();
                }
            },
            items: [{
                xtype: 'checkbox',
                fieldLabel: 'Rapport de commission',
                name: null, //FIXME
                boxLabel: 'Recu'
            }, {
                xtype: 'checkbox',
                fieldLabel: 'Cahier des charges',
                name: null, //FIXME
                boxLabel: 'Recu'
            }, {
                xtype: 'checkbox',
                fieldLabel: 'CV et liste publications',
                name: null, //FIXME
                boxLabel: 'Recu'
            }, {
                xtype: 'checkbox',
                fieldLabel: 'Déclaration de santé',
                name: null, //FIXME
                boxLabel: 'Recu'
            }]
        }, {
            xtype: 'fieldset',
            title: 'Imputation',
            items: [{
                xtype: 'textfield',
                fieldLabel: 'Fonds',
                name: null, //FIXME
            }, {
                xtype: 'textfield',
                fieldLabel: 'Centre financier',
                name: null, //FIXME
            }, {
                xtype: 'textfield',
                fieldLabel: 'Unité structurelle',
                name: null, //FIXME
            }, {
                xtype: 'textfield',
                fieldLabel: 'Numéro de projet',
                name: null, //FIXME
            }]
        }]
        var me = this;
        me.callParent();
    }
});
