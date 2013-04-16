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
                fieldLabel: 'Dénomination',
                name: 'personne_denomination_id',
                displayField: 'nom',
                valueField: 'id',
                store: Ext.create('iafbm.store.PersonneDenomination')
            }, {
                xtype: 'ia-combo',
                fieldLabel: 'Canton d\'origine',
                name: 'canton_id',
                displayField: 'nom',
                valueField: 'id',
                store: Ext.create('iafbm.store.Canton'),
            }, {
                xtype: 'ia-combo',
                fieldLabel: 'Pays d\'origine',
                name: 'pays_id',
                displayField: 'nom',
                valueField: 'id',
                store: new iafbm.store.Pays({})
            }, {
                xtype: 'ia-combo',
                fieldLabel: 'Permis de séjour',
                name: 'permis_id',
                displayField: 'nom',
                valueField: 'id',
                store: Ext.create('iafbm.store.Permis'),
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

Ext.define('iafbm.form.CommissionPropositionNomination', {
    extend: 'Ext.ia.form.Panel',
    store: Ext.create('iafbm.store.CommissionPropositionNomination'), // FIXME: this should not be necessary
    //
    getToobarButtons: function(commission_id) {
        var url = [
            x.context.baseuri,
            '/print/proposition_nomination/',
            commission_id
        ].join('');
        return [{
            xtype: 'button',
            text: 'Visualiser',
            iconCls: 'icon-details',
            handler: function() {window.open(url+'?html')}
        }, {
            xtype: 'button',
            text: 'Imprimer',
            iconCls: 'icon-print',
            handler: function() {window.open(url)}
        }];
    },
    //
    initComponent: function() {
        // Form toolbar buttons
        this.tbar = this.getToobarButtons(this.fetch.params.commission_id);
        this.tbar.unshift('-');
        // Form defaults
        this.defaults = {
            width: '100%',
            defaults: {
                width: 400,
                labelWidth: 150
            }
        };
        // Form items
        this.items = [{
            xtype: 'fieldcontainer',
            layout: 'hbox',
            items: [{
                xtype: 'ia-combo',
                fieldLabel: 'Candidat',
                name: 'candidat_id',
                displayField: '_display', // TODO: Use template instead?
                valueField: 'id',
                labelAlign: 'right',
                labelWidth: 160,
                width: 500,
                store: new iafbm.store.Candidat({
                    params: { commission_id: this.fetch.params.commission_id },
                    listeners: {
                        load: function(store, records) {
                            store.add({id: null, nom: '(aucun)'});
                        }
                    }
                }),
                editable: false,
                listeners: {
                    select: function(combo, records) {
                        if (!records[0].get('id')) this.setValue(null);
                    }
                }
            }, {
                xtype: 'button',
                text: 'Formulaire candidat',
                iconCls: 'icon-edit',
                width: 150,
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
                        })
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
            }]
        }, {
            xtype: 'fieldset',
            title: 'Proposition de nomination',
            items: [{
                xtype: 'textfield',
                fieldLabel: 'Objet',
                name: 'objet'
            }, {
                xtype: 'textfield',
                fieldLabel: 'Discipline générale',
                name: 'discipline_generale'
            }, {
                xtype: 'ia-combo',
                fieldLabel: 'Titre proposé',
                name: 'activite_id',
                displayField: 'activite_nom_abreviation',
                valueField: 'id',
                store: Ext.create('iafbm.store.Activite', {params: {
                    xorder_by: 'section_code,activite_type_nom,activite_nom_abreviation'
                }}),
                editable: false,
                // Custom rendering template for data display
                displayTpl: '<tpl for=".">{section_code} / {activite_type_nom} / {activite_nom_abreviation}</tpl>',
                listConfig: {
                    getInnerTpl: function() {
                        // Reuses displayTpl template
                        return this.pickerField.displayTpl.html;
                    }
                }
            }, {
                // FIXME: set checkbox as checked when commission_proposition_nomination store loaded
                //        contrat_debut is null (TODO)
                xtype: 'fieldcontainer',
                layout: 'hbox',
                fieldLabel: 'Début du contrat',
                items: [{
                    xtype: 'ia-datefield',
                    name: 'contrat_debut'
                }, {
                    xtype: 'displayfield',
                    value: '&nbsp;'
                }, {
                    xtype: 'checkbox',
                    boxLabel: 'Au plutot tôt',
                    name: 'contrat_debut_au_plus_tot',
                    handler: function() {
                        var datefield = this.up().down('datefield');
                        if (this.checked) {
                            datefield.hide();
                        } else {
                            datefield.show();
                        }
                    }
                }]
            }, {
                xtype: 'ia-datefield',
                fieldLabel: 'Fin du contrat',
                name: 'contrat_fin'
            }, {
                xtype: 'fieldcontainer',
                layout: 'hbox',
                fieldLabel: 'Taux d\'activité /<br> Charge horaire',
                items: [{
                    xtype: 'numberfield',
                    name: 'charge_horaire',
                    width: 80,
                    margin: '0 2 0 0'
                }, {
                    xtype: 'ia-combo',
                    editable: false,
                    name: 'grandeur_id',
                    width: 161,
                    displayField: 'unite_symbole',
                    valueField: 'id',
                    store: Ext.create('iafbm.store.Grandeur', {
                        params: { 'id[]': [1,2,3,4] }
                    })
                }]
            }, {
                xtype: 'textfield',
                fieldLabel: 'Indemnité (CHF)',
                name: 'indemnite'
            }, {
                xtype: 'textfield',
                fieldLabel: 'Titre du cours',
                name: 'titre_cours'
            }, {
                // FIXME: Stefan va clarifier (cf. PV workshop)
                // Data: commission_validation: Décanat or CF? ask.
                xtype: 'ia-combo',
                fieldLabel: 'Date préavis',
                name: 'date_preavis_champs',
                displayField: 'display',
                valueField: 'value',
                store: Ext.create('Ext.data.ArrayStore', {
                    autoDestroy: true,
                    fields: ['display', 'value'],
                    data: [],
                    store: Ext.create('iafbm.store.CommissionValidation', {
                        params: { commission_id: this.fetch.params.commission_id },
                    }),
                    listeners: { load: function() {
                        var store = this;
                        this.store.load(function(records) {
                            var record = records[0],
                                addDate = function(label, field) {
                                    var date = record.get(field),
                                        date = date ? Ext.Date.format(date, 'd.m.Y') : '-';
                                    store.add({
                                        display: [label, ': ', date].join(''),
                                        value: field
                                    });
                            };

                            store.removeAll();
                            addDate('Préavis Décanat', 'decanat_date');
                            addDate('Préavis CF', 'cf_date');
                        });
                    }}
                })
            }, {
                xtype: 'ia-textarea',
                fieldLabel: 'Observations',
                name: 'observations',
                grow: true

            }, {
                xtype: 'ia-datefield',
                fieldLabel: 'Date proposition',
                name: 'date_proposition'
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
                name: 'annexe_rapport_commission',
                boxLabel: 'Reçu'
            }, {
                xtype: 'checkbox',
                fieldLabel: 'Cahier des charges',
                name: 'annexe_cahier_des_charges',
                boxLabel: 'Reçu'
            }, {
                xtype: 'checkbox',
                fieldLabel: 'CV et liste publications',
                name: 'annexe_cv_publications',
                boxLabel: 'Reçu'
            }, {
                xtype: 'checkbox',
                fieldLabel: 'Déclaration de santé',
                name: 'annexe_declaration_sante',
                boxLabel: 'Reçu'
            }]
        }, {
            xtype: 'fieldset',
            title: 'Imputation',
            items: [{
                xtype: 'textfield',
                fieldLabel: 'Fonds',
                name: 'imputation_fonds',
            }, {
                xtype: 'textfield',
                fieldLabel: 'Centre financier',
                name: 'imputation_centre_financier',
            }, {
                xtype: 'textfield',
                fieldLabel: 'Unité structurelle',
                name: 'imputation_unite_structurelle',
            }, {
                xtype: 'textfield',
                fieldLabel: 'Numéro de projet',
                name: 'imputation_numero_projet',
            }]
        }]
        //
        this.callParent();
    }
});