<h1><?php echo "Commission n° {$d['id']} - {$d['nom']}" ?></h1>

<div id="target"></div>

<script type="text/javascript">

Ext.onReady(function() {

    var form_apercu = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        // FIXME: remove this after store=null bug source has been discovered
        store: Ext.create('iafbm.store.Commission'),
        fetch: {
            model: iafbm.model.Commission,
            id: <?php echo $d['id'] ?>
        },
        defaults: {
            anchor: '100%'
        },
        items: [{
            xtype: 'fieldcontainer',
            combineErrors: true,
            layout: 'hbox',
            height: 35,
            defaults: { labelStyle: 'font-weight:bold' },
            items: [{
                    xtype: 'displayfield',
                    name: 'commission_type_racine',
                    fieldLabel: 'Type',
                    labelWidth: 33,
                    width: 350
                },{
                    xtype: 'ia-combo',
                    fieldLabel: 'Etat',
                    displayField: 'nom',
                    valueField: 'id',
                    store: Ext.create('iafbm.store.CommissionEtat'),
                    name: 'commission_etat_id',
                    allowBlank: false,
                    labelWidth: 33,
                    width: 180,
                    // Prevents from displaying 'closed' state in combo options
                    queryMode: 'local',
                    listeners: {
                        beforequery: function(queryEvent, eventOpts) {
                            this.store.clearFilter();
                            this.store.filter('id', /[^3]/);
                            queryEvent.cancel = true;
                            this.expand();
                        },
                        collapse: function() {
                            this.store.clearFilter();
                        }
                    },
                },{
                    xtype: 'ia-combo',
                    fieldLabel: 'Section',
                    displayField: 'code',
                    valueField: 'id',
                    store: Ext.create('iafbm.store.Section'),
                    name: 'section_id',
                    allowBlank: false,
                    labelWidth: 47,
                    width: 120,
                    margin: '0 0 0 100'
                }
            ]
        }, {
            baseCls: 'title',
            html: 'Institut'
        }, {
            xtype: 'textfield',
            name: 'institut'
        }, {
            baseCls: 'title',
            html: 'Commentaire'
        }, {
            xtype: 'ia-textarea',
            name: 'commentaire',
            growMin: 21,
            grow: true
        }, {
            baseCls: 'title',
            html: 'Composition'
        }, new Ext.ia.selectiongrid.Panel({
            title: 'Membres nominatifs',
            width: 857,
            height: 250,
            combo: {
                store: new iafbm.store.Personne({
                    params: {
                        xjoin: 'pays',
                        xreturn: 'id,nom,prenom,pays.nom AS pays_nom,pays.code AS pays_code'
                    }
                })
            },
            grid: {
                store: new iafbm.store.CommissionMembre({
                    params: {
                        commission_id: <?php echo $d['id'] ?>
                    },
                    sorters: [{
                        property : 'commission_fonction_position',
                        direction: 'ASC'
                    }]
                }),
                columns: iafbm.columns.CommissionMembre
            },
            makeData: function(record) {
                return {
                    personne_id: record.get('id'),
                    commission_fonction_id: 1,
                    commission_id: <?php echo $d['id'] ?>,
                    personne_nom: record.get('nom'),
                    personne_prenom: record.get('prenom')
                }
            },
            tbar: ['->', {
                xtype: 'button',
                text: 'Imprimer',
                iconCls: 'icon-print',
                handler: function() {
                    var id = <?php echo $d['id'] ?>,
                        url = [x.context.baseuri, '/print/commissions_membres/', id].join('');
                    location.href = url;
                }
            }, '-', {
                xtype: 'button',
                text: 'Visualiser',
                iconCls: 'icon-details',
                handler: function() {
                    var id = <?php echo $d['id'] ?>,
                        url = [x.context.baseuri, '/print/commissions_membres/', id, '?html'].join('');
                    window.open(url);
                }
            }, '-', {
                xtype: 'button',
                text: 'Export adresses (CSV)',
                iconCls: 'icon-get',
                handler: function() {
                    var id = <?php echo $d['id'] ?>,
                        url = [x.context.baseuri, '/commissions_membres/do/export/', id, '?xformat=csv'].join('');
                    location.href = url;
                }
            }]
        }), {
            xtype: 'ia-editgrid',
            title: 'Membres non nominatifs',
            width: 857,
            height: 200,
            toolbarButtons: ['add', 'delete'],
            store: new iafbm.store.CommissionMembreNonominatif({
                params: { commission_id: <?php echo $d['id'] ?> },
                sorters: [{
                    property : 'commission_fonction_position',
                    direction: 'ASC'
                }]
            }),
            newRecordValues: { commission_id: '<?php echo $d['id'] ?>' },
            columns: iafbm.columns.CommissionMembreNonominatif,
            bbar: null
        }]
    });

    var store_creation_etat = new iafbm.store.CommissionCreationEtat();
    var form_creation = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        // FIXME: remove this unused store if no bugs are dectected
        store: Ext.create('iafbm.store.CommissionCreation'),
        fetch: {
            model: iafbm.model.CommissionCreation,
            params: { commission_id: <?php echo $d['id'] ?> }
        },
        defaults: {
            layout: 'hbox',
            labelWidth: 190,
            defaults: {
                margin: '0 3 0 0'
            }
        },
        items: [{
            baseCls: 'title',
            html: 'Phase de création'
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Préavis du Décanat',
            iaDisableFor: [4, 5],
            items: [{
                xtype:'ia-datefield',
                name: 'preavis_decanat',
            }, {
                xtype: 'ia-combo',
                displayField: 'nom',
                valueField: 'id',
                store: store_creation_etat,
                name: 'etat_preavis_decanat',
            }]
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Préavis CCP',
            iaDisableFor: [1, 2, 4, 5, 6],
            items: [{
                xtype:'ia-datefield',
                name: 'preavis_ccp'
            }, {
                xtype: 'ia-combo',
                displayField: 'nom',
                valueField: 'id',
                store: store_creation_etat,
                name: 'etat_preavis_ccp'
            }]
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Préavis CPA',
            iaDisableFor: [4, 5],
            items: [{
                xtype:'ia-datefield',
                name: 'preavis_cpa'
            }, {
                xtype: 'ia-combo',
                displayField: 'nom',
                valueField: 'id',
                store: store_creation_etat,
                name: 'etat_preavis_cpa'
            }]
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Autorisation du CDir',
            iaDisableFor: [2, 4, 5],
            items: [{
                xtype:'ia-datefield',
                name: 'autorisation'
            }, {
                xtype: 'ia-combo',
                displayField: 'nom',
                valueField: 'id',
                store: store_creation_etat,
                name: 'etat_autorisation'
            }]
        }, {
            xtype:'ia-datefield',
            fieldLabel: 'Choix composition par Décanat',
            name: 'decision',
            iaDisableFor: []
        }, {
            xtype:'ia-datefield',
            fieldLabel: 'Annonce journaux OK le',
            name: 'annonce',
            iaDisableFor: [2, 3, 4, 5, 6]
        }, {
            xtype:'ia-datefield',
            fieldLabel: 'Composition OK le',
            name: 'composition',
            iaDisableFor: []
        }, {
            xtype:'ia-datefield',
            fieldLabel: 'Validation de la composition par le vice-recteur',
            name: 'composition_validation',
            iaDisableFor: [2]
        }, {
            baseCls: 'title',
            html: 'Commentaire'
        }, {
            xtype: 'ia-textarea',
            name: 'commentaire',
            anchor: '100%',
            growMin: 21,
            grow: true
        }]
    });

    var form_candidat = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        // FIXME: remove this after store=null bug source has been discovered
        store: Ext.create('iafbm.store.CommissionCandidatCommentaire'),
        fetch: {
            model: iafbm.model.CommissionCandidatCommentaire,
            params: { commission_id: <?php echo $d['id'] ?> }
        },
        defaults: {
            anchor: '100%'
        },
        items: [{
            baseCls: 'title',
            html: 'Candidats'
        }, {
            xtype:'ia-editgrid',
            iaDisableFor: [2],
            width: 858,
            height: 289,
            toolbarButtons: ['add', 'delete', 'search'],
            store: new iafbm.store.Candidat({
                params: {commission_id: <?php echo $d['id'] ?>}
            }),
            searchParams: { xwhere: 'query' },
            columns: iafbm.columns.Candidat,
            pageSize: 10,
            addItem: function() {
                var me = this;
                var popup = new Ext.ia.window.Popup({
                    title: 'Créer un candidat',
                    item: new iafbm.form.Candidat({
                        frame: false,
                        record: new iafbm.model.Candidat({
                            commission_id: <?php echo $d['id'] ?>
                        })
                    }),
                    listeners: { beforeclose: function() {
                        me.store.load();
                    }}
                });
            },
        }, {
            html: '&nbsp;',
            border: false
        }, {
            xtype:'ia-datefield',
            labelWidth: 200,
            labelAlign: 'left',
            anchor: null,
            fieldLabel: 'Date de clôture des candidatures',
            name: 'date_cloture',
            iaDisableFor: [2, 3, 4, 5, 6]
        }, {
            baseCls: 'title',
            html: 'Commentaire'
        }, {
            xtype: 'ia-textarea',
            name: 'commentaire',
            growMin: 21,
            grow: true
        }]
    });

    var form_travail = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        // FIXME: remove this after store=null bug source has been discovered
        store: Ext.create('iafbm.store.CommissionTravail'),
        fetch: {
            model: iafbm.model.CommissionTravail,
            params: { commission_id: <?php echo $d['id'] ?> }
        },
        defaults: {
            anchor: '100%'
        },
        items: [{
            baseCls: 'title',
            html: 'Phase de travail'
        }, {
            xtype: 'fieldcontainer',
            combineErrors: true,
            layout: 'hbox',
            defaults: {
                border: false,
                flex: 1,
                defaults: {
                    labelWidth: 135
                }
            },
            items: [{
                items: [{
                    xtype: 'displayfield',
                    value: '<b>Séances</b>'
                }, {
                    xtype:'ia-editgrid',
                    iaDisableFor: [],
                    frame: false,
                    width: 400,
                    height: 150,
                    toolbarButtons: ['add', 'delete'],
                    bbar: null,
                    store: new iafbm.store.CommissionTravailEvenement({
                        params: { commission_id: '<?php echo $d['id'] ?>' },
                        sorters: [{property: 'date', direction: 'DESC'}]
                    }),
                    newRecordValues: { commission_id: '<?php echo $d['id'] ?>' },
                    columns: [{
                        header: "Type",
                        dataIndex: 'commission_travail_evenement_type_id',
                        width: 200,
                        xtype: 'ia-combocolumn',
                        field: {
                            xtype: 'ia-combo',
                            store: new iafbm.store.CommissionTravailEvenementType(),
                            valueField: 'id',
                            displayField: 'nom',
                            allowBlank: false
                        }
                    },{
                        header: "Date",
                        dataIndex: 'date',
                        width: 75,
                        xtype: 'ia-datecolumn',
                        field: {
                            xtype: 'ia-datefield',
                            allowBlank: false
                        }
                    },{
                        header: "PV Reçu",
                        dataIndex: 'proces_verbal',
                        flex: 1,
                        xtype: 'checkcolumn',
                        field: {
                            xtype: 'checkbox',
                            disabled: true
                        }
                    },{
                        header: "Durée",
                        dataIndex: 'duree',
                        width: 71,
                        xtype: 'templatecolumn',
                        tpl: '{duree}<tpl if="duree!=null"> minutes</tpl>',
                        field: {
                            xtype: 'numberfield',
                            minValue: 0,
                            step: 15,
                            allowDecimals: false,
                            editable: false
                        }
                    }]
                }]
            }, {
                items: [{
                    xtype: 'displayfield',
                    value: '<b>Choix des candidats</b>',
                }, {
                    xtype: 'checkbox',
                    hideLabel: true,
                    boxLabel: 'Aucun, pas de proposition de nomination',
                    padding: '5 0 15 0',
                    name: 'aucun_candidat',
                    iaDisableFor: [2]
                }, {
                    xtype: 'ia-combo',
                    fieldLabel: 'Primo loco',
                    displayField: '_display',
                    valueField: 'id',
                    store: new iafbm.store.Candidat({
                        params: { commission_id: <?php echo $d['id'] ?> }
                    }),
                    name: 'primo_loco',
                    iaDisableFor: [2, 3, 4, 5]
                }, {
                    xtype: 'ia-combo',
                    fieldLabel: 'Secundo loco',
                    displayField: '_display',
                    valueField: 'id',
                    store: new iafbm.store.Candidat({
                        params: { commission_id: <?php echo $d['id'] ?> }
                    }),
                    name: 'secondo_loco',
                    iaDisableFor: [2, 3, 4, 5]
                }, {
                    xtype: 'ia-combo',
                    fieldLabel: 'Tertio loco',
                    displayField: '_display',
                    valueField: 'id',
                    store: new iafbm.store.Candidat({
                        params: { commission_id: <?php echo $d['id'] ?> }
                    }),
                    name: 'tertio_loco',
                    iaDisableFor: [2, 3, 4, 5]
                }, {
                    xtype: 'ia-datefield',
                    fieldLabel: 'Délai donné<br>pour envoi rapport',
                    name: 'delai_envoi_rapport',
                    iaDisableFor: []
                }]
            }]
        }, {
            baseCls: 'title',
            html: 'Commentaire'
        }, {
            xtype: 'ia-textarea',
            name: 'commentaire',
            growMin: 21,
            grow: true
        }]
    });

    var store_validation_etat = new iafbm.store.CommissionValidationEtat();
    var form_validation = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        // FIXME: remove this after store=null bug source has been discovered
        store: Ext.create('iafbm.store.CommissionValidation'),
        fetch: {
            model: iafbm.model.CommissionValidation,
            params: { commission_id: <?php echo $d['id'] ?> }
        },
        defaults: {
            anchor: '100%',
            layout: 'hbox',
            labelWidth: 160,
            defaults: {
                margin: '0 3 0 0'
            },
        },
        items: [{
            baseCls: 'title',
            html: 'Validation de rapport'
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: '&nbsp;',
            labelSeparator: '',
            defaults: {
                labelStyle: 'font-weight:bold',
                labelSeparator: ''
            },
            items: [{
                xtype: 'displayfield',
                fieldLabel: 'Date',
                width: 156
            }, {
                xtype: 'displayfield',
                fieldLabel: 'Décision</span>',
                width: 156
            }, {
                xtype: 'displayfield',
                fieldLabel: 'Commentaire</span>'
            }]
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Réception du rapport',
            items: [{
                xtype: 'ia-datefield',
                name: 'reception_rapport',
            }],
            iaDisableFor: []
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Validation par le Décanat',
            items: [{
                xtype: 'ia-datefield',
                name: 'decanat_validation_date',
            }, {
                xtype: 'ia-combo',
                displayField: 'nom',
                valueField: 'id',
                store: store_validation_etat,
                name: 'decanat_validation_etat'
            }, {
                xtype: 'ia-textarea',
                name: 'decanat_validation_commentaire',
                anchor: '100%',
                width: 381,
                growMin: 21,
                grow: true
            }],
            iaDisableFor: []
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Commentaire DG-CHUV',
            items: [{
                xtype: 'ia-datefield',
                name: 'dg_commentaire_date'
            }, {
                xtype: 'combo',
                disabled: 'true',
                store: Ext.create('Ext.data.Store', {fields:[], data: []})
            }, {
                xtype: 'ia-textarea',
                name: 'dg_commentaire_commentaire',
                anchor: '100%',
                width: 381,
                growMin: 21,
                grow: true
            }],
            iaDisableFor: []
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Validation par le CF',
            items: [{
                xtype: 'ia-datefield',
                name: 'cf_validation_date'
            }, {
                xtype: 'ia-combo',
                displayField: 'nom',
                valueField: 'id',
                store: store_validation_etat,
                name: 'cf_validation_etat'
            }, {
                xtype: 'ia-textarea',
                name: 'cf_validation_commentaire',
                anchor: '100%',
                width: 381,
                growMin: 21,
                grow: true
            }],
            iaDisableFor: []
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Validation par le CDir',
            items: [{
                xtype: 'ia-datefield',
                name: 'cdir_validation_date'
            }, {
                xtype: 'ia-combo',
                displayField: 'nom',
                valueField: 'id',
                store: store_validation_etat,
                name: 'cdir_validation_etat'
            }, {
                xtype: 'ia-textarea',
                name: 'cdir_validation_commentaire',
                anchor: '100%',
                width: 381,
                growMin: 21,
                grow: true
            }],
            iaDisableFor: []
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Nomination par le CDir',
            items: [{
                xtype: 'ia-datefield',
                name: 'cdir_nomination_date'
            }, {
                xtype: 'ia-combo',
                displayField: 'nom',
                valueField: 'id',
                store: store_validation_etat,
                name: 'cdir_nomination_etat'
            }, {
                xtype: 'ia-textarea',
                name: 'cdir_nomination_commentaire',
                anchor: '100%',
                width: 381,
                growMin: 21,
                grow: true
            }],
            iaDisableFor: [2]
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Proposition de nomination',
            iaDisableFor: [2],
            items: function() {
                var items = [{
                    xtype: 'ia-datefield',
                    name: 'envoi_proposition_nomination',
                }, {
                    xtype: 'button',
                    text: 'Formulaire',
                    iconCls: 'icon-details',
                    handler: function() {
                        var me = this,
                            popup = new Ext.ia.window.Popup({
                            title: 'Détails',
                            item: new iafbm.form.CommissionPropositionNomination({
                                fetch: {
                                    model: iafbm.model.CommissionPropositionNomination,
                                    params: { commission_id: '<?php echo $d['id'] ?>' }
                                },
                                frame: false
                            })
                        });
                    }
                }];
                // Reuses Proposition Nomination form toolbar buttons
                items = items.concat(
                    iafbm.form.CommissionPropositionNomination.prototype.getToobarButtons(
                        <?php echo $d['id'] ?>
                    )
                );
                // Applies iaDisableFor to buttons
                // (dirty but easy solution to prevent button from being clicked)
                items.map(function(e) {
                    e.iaDisableFor = [2];
                    return e;
                });
                return items;
            }()
        }, {
            baseCls: 'title',
            html: 'Commentaire'
        }, {
            xtype: 'ia-textarea',
            name: 'commentaire',
            growMin: 21,
            grow: true
        }]
    });

    var form_finalisation = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        // FIXME: remove this after store=null bug source has been discovered
        store: Ext.create('iafbm.store.CommissionFinalisation'),
        fetch: {
            model: iafbm.model.CommissionFinalisation,
            params: { commission_id: <?php echo $d['id'] ?> }
        },
        defaults: {
            anchor: '100%',
            layout: 'hbox',
            combineErrors: true,
            msgTarget: 'under',
            labelWidth: 170,
            defaults: {
                margin: '0 3 0 0'
            },
        },
        items: [{
            baseCls: 'title',
            html: 'Finalisation'
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Candidat retenu par le CDir',
            items: [{
                xtype: 'ia-combo',
                width: 567,
                name: 'candidat_id',
                displayField: '_display',
                valueField: 'id',
                store: new iafbm.store.Candidat({
                    params: { commission_id: <?php echo $d['id'] ?> }
                }),
                // Reloads candidats on drowndown expand because it is subject to change
                listeners: { expand: function() { this.store.load() } }
            }],
            iaDisableFor: [2]
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Réception du contrat',
            items: [{
                xtype: 'ia-datefield',
                name: 'reception_contrat_date',
            }, {
                xtype: 'ia-textarea',
                name: 'reception_contrat_commentaire',
                anchor: '100%',
                width: 411,
                growMin: 21,
                grow: true
            }],
            iaDisableFor: [2]
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: "Début d'activité",
            items: [{
                xtype: 'ia-datefield',
                name: 'debut_activite'
            }],
            iaDisableFor: [2]
        }, {
            baseCls: 'title',
            html: 'Commentaire'
        }, {
            xtype: 'ia-textarea',
            name: 'commentaire',
            growMin: 21,
            grow: true
        }, {
            baseCls: 'title',
            html: 'Archivage'
        }, {
            xtype: 'button',
            text: '<span style="font-weight:bold;font-size:18px">Clôturer</span>',
            height: 50,
            // Disables button if commission is already 'closed'
            listeners: {
                afterrender: function() {
                    var me = this,
                        form = Ext.getCmp('apercu').down('form');
                    if (form.record) {
                        me.disableIf(form.getRecord());
                    }
                    form.on('load', function() {
                        me.disableIf(this.getRecord(), form.store);
                    });
                }
            },
            disableIf: function(record, store) {
                // Disables 'close' button
                // if commission not already closed & form is not versioned
                var versioned = store && store.params.xversion;
                var enable = record.get('commission_etat_id')!=3 && !versioned;
                this.setDisabled(!enable);
            },
            // Click logic
            handler: function() {
                var me = this;
                Ext.Msg.confirm(
                    'Clôturer la commission',
                    'Une fois clôturée, la commission ne peut plus être modifiée. \
                    Cette action est irreversible. <br/><br/> \
                    Voulez-vous clôturer la commission ?',
                    function(is) {
                        if (is=='yes') me.archiveCommission()
                    }
                );
            },
            archiveCommission: function() {
                var form = Ext.getCmp('apercu').down('form'),
                    record = form.getRecord();
                record.set('commission_etat_id', 3);
                record.save();
                form.loadRecord();
            }
        }]
    });

    // Panels ids are used for URL hash
    var tabPanel = Ext.createWidget('ia-tabpanel-commission', {
        activeTab: 0,
        plain: true,
        // Disabling deferredRender avoids sizing glitches (mostly on firefox)
        deferredRender: false,
        defaults: {
            autoScroll: true,
        },
        items: [{
            id: 'apercu',
            title: 'Apercu général',
            items: form_apercu,
            iconCls: 'tab-icon-unknown'
        }, {
            id: 'creation',
            title: 'Phase de création',
            items: form_creation,
            iconCls: 'tab-icon-unknown'
        }, {
            id: 'candidat',
            title: 'Candidat',
            items: form_candidat,
            iconCls: 'tab-icon-unknown'
        }, {
            id: 'travail',
            title: 'Phase de travail',
            items: form_travail,
            iconCls: 'tab-icon-unknown'
        }, {
            id: 'validation',
            title: 'Validation de rapport',
            items: form_validation,
            iconCls: 'tab-icon-unknown'
        }, {
            id: 'finalisation',
            title: 'Finalisation',
            items: form_finalisation,
            iconCls: 'tab-icon-unknown'
        }],
        listeners: {
            tabchange: function(tabPanel, newCard, oldCard, options) {
                // Automatic url hash (#) update on tab selection
                document.location.hash = tabPanel.getActiveTab().id;
            },
            beforerender: function() {
                // Automatic tab selection according url hash (#)
                var tabId = document.location.hash.replace("#", "");
                this.setActiveTab(tabId);
            }
        }
    });

    var panel = Ext.createWidget('panel', {
        renderTo: 'target',
        border: false,
        bodyStyle: 'background-color: transparent',
        items: [{
            xtype: 'ia-versioning',
            comboConfig: {
                modelname: 'commission',
                modelid: <?php echo $d['id'] ?>,
                getTopLevelComponent: function() {
                    return this.up('panel').down('tabpanel');
                }
            },
            formConfig: {
                getForm: function() {
                    return this.up('panel').down('tabpanel').getActiveTab().down('form');
                }
            }
        },
            tabPanel
        ]
    });

});

</script>