Ext.define('iafbm.form.Personne.Coordonnees', {
    extend: 'Ext.ia.form.Panel',
    alias: 'widget.ia-form-personne-coordonnees',
    store: Ext.create('iafbm.store.Personne'), // FIXME: this should not be necessary
    initComponent: function() {
        this.items = [{
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
                width: 15
            }, {
                xtype: 'fieldcontainer',
                flex: 1,
                items: [
                    this._createAdresses(),
                    this._createTelephones(),
                    this._createEmails()
                ]
            }]
        }];
        //
        var me = this;
        me.callParent();
    },
    switchType: function() {
        var type = this.getValue();
        this.up('tabpanel').cascade(function(c) {
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

Ext.define('iafbm.form.Personne.Activites', {
    extend: 'Ext.ia.form.Panel',
    alias: 'widget.ia-form-personne-activites',
    store: Ext.create('iafbm.store.Personne'), // FIXME: this should not be necessary
    initComponent: function() {
        this.items = [
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
        }];
        this.callParent();
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
                width: 150,
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
    }
});


Ext.define('iafbm.form.Personne.Commissions', {
    extend: 'Ext.ia.form.Panel',
    alias: 'widget.ia-form-personne-commissions',
    store: Ext.create('iafbm.store.Personne'), // FIXME: this should not be necessary
    initComponent: function() {
        this.items = [
            this._createCommissions()
        ]
        this.callParent();
    },
    _createCommissions: function() {
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

Ext.define('iafbm.form.Personne.TabPanel', {
    extend: 'Ext.ia.tab.Panel',
    alias: 'widget.ia-form-personne-tabpanel',
    activeTab: 0,
    plain: true,
    deferredRender: false,
    defaults :{
        autoScroll: true,
    },
    initComponent: function() {
        this.items = [{
            title: 'Coordonnées',
            items: [{
                xtype: 'ia-form-personne-coordonnees',
                fetch: this.fetch
            }]
        },{
            title: 'Carrière',
            items: [{
                xtype: 'ia-form-personne-activites',
                fetch: this.fetch
            }]
        },{
            title: 'Commissions',
            items: [{
                xtype: 'ia-form-personne-commissions',
                fetch: this.fetch
            }]
        }];
        var me = this;
        me.callParent();
    }
});

Ext.define('iafbm.form.Personne', {
    extend: 'Ext.panel.Panel',
    border: false,
    initComponent: function() {
        this.items = [{
            xtype: 'ia-versioning',
            comboConfig: {
                modelname: 'personne',
                modelid: this.fetch.id,
                getTopLevelComponent: function() {
                    return this.up('panel').down('tabpanel');
                }
            },
            formConfig: {
                getForm: function() {
                    return this.up('panel').down('tabpanel').getActiveTab().down('form');
                }
            },
            padding: 5
        }, {
            xtype: 'ia-form-personne-tabpanel',
            fetch: this.fetch
        }]
        var me = this;
        me.callParent();
    }
});