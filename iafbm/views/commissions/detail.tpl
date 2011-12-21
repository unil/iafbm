<div class="title"><?php echo "Commission n° {$d['id']} - {$d['nom']}" ?></div>

<div id="target"></div>

<script type="text/javascript">

Ext.onReady(function() {

    // Shared Candidat store
    var store_candidat = new iafbm.store.Candidat();

    var form_apercu = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        store: Ext.create('iafbm.store.Commission'),
        fetch: {
            model: iafbm.model.Commission,
            id: <?php echo $d['id'] ?>
        },
        defaults: {
            anchor: '100%'
        },
        items: [{
            html: '<h1>Cette commission est clôturée</h1>',
            border: false,
            style: {
                'border': '1px solid #500',
                'margin': '0 0 20px 0'
            },
            bodyStyle: {
                'padding': '10px',
                'color': '#300',
                'background-color': '#fdd'
            },
            // Display logic
            hidden: true,
            listeners: {added: function() {
                var me = this,
                    form = this.up('form');
                form.on('load', function() {
                    if (this.getRecord().get('commission_etat_id') == 3) {
                        me.show();
                    }
                });
            }}
        },{
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
            html: 'Commentaire'
        }, {
            xtype: 'ia-textarea',
            name: 'commentaire',
            growMin: 21,
            grow: true
        }, new Ext.ia.selectiongrid.Panel({
            title: 'Composition',
            width: 857,
            height: 350,
            plugins: [new Ext.ia.grid.plugin.RowEditing()],
            combo: {
                store: new iafbm.store.Personne(),
            },
            grid: {
                store: new iafbm.store.CommissionMembre(),
                params: {
                    commission_id:<?php echo $d['id'] ?>
                },
                columns: iafbm.columns.CommissionMembre
            },
            makeData: function(record) {
                return {
                    personne_id: record.get('id'),
                    commission_fonction_id: 1,
                    commission_id: <?php echo $d['id'] ?>,
                    actif: 1
                }
            }
        })/*, {
            xtype: 'ia-history'
        }*/]
    });

    var form_creation = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        store: Ext.create('iafbm.store.CommissionCreation'),
        fetch: {
            model: iafbm.model.CommissionCreation,
            params: { commission_id: <?php echo $d['id'] ?> }
        },
        defaults: {
            anchor: '100%'
        },
        items: [{
            baseCls: 'title',
            html: 'Phase de création'
        }, {
            xtype: 'fieldcontainer',
            combineErrors: true,
            layout: 'hbox',
            defaults: {
                border: false,
                flex: 1,
                defaults: {
                    labelWidth: 180
                }
            },
            items: [{
                items: [{
                    xtype:'ia-datefield',
                    fieldLabel: 'Date de décision du Décanat',
                    name: 'decision'
                }, {
                    xtype:'ia-datefield',
                    fieldLabel: 'Préavis positif CPA',
                    name: 'preavis'
                }, {
                    xtype:'ia-datefield',
                    fieldLabel: 'Autorisation du CDir',
                    name: 'autorisation'
                }]
            }, {
                items: [{
                    xtype:'ia-datefield',
                    fieldLabel: 'Annonce journaux OK le',
                    name: 'annonce'
                }, {
                    xtype:'ia-datefield',
                    fieldLabel: 'Composition OK le',
                    name: 'composition'
                }, {
                    xtype:'ia-datefield',
                    fieldLabel: 'Validation de la composition par le vice-recteur',
                    name: 'composition_validation'
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
        }/*, {
            xtype: 'ia-history'
        }*/]
    });

    var form_candidat = Ext.create('Ext.ia.form.CommissionPhasePanel', {
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
            width: 858,
            height: 289,
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
            baseCls: 'title',
            html: 'Commentaire'
        }, {
            xtype: 'ia-textarea',
            name: 'commentaire',
            growMin: 21,
            grow: true
        }/*, {
            xtype: 'ia-history'
        }*/]
    });

    var form_travail = Ext.create('Ext.ia.form.CommissionPhasePanel', {
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
                    frame: false,
                    width: 400,
                    height: 150,
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
                    height: 25
                }, {
                    xtype: 'ia-combo',
                    fieldLabel: 'Primo Loco',
                    displayField: '_display',
                    valueField: 'id',
                    store: store_candidat,
                    name: 'primo_loco',
                }, {
                    xtype: 'ia-combo',
                    fieldLabel: 'Secondo Loco',
                    displayField: '_display',
                    valueField: 'id',
                    store: store_candidat,
                    name: 'secondo_loco'
                }, {
                    xtype: 'ia-combo',
                    fieldLabel: 'Tertio Loco',
                    displayField: '_display',
                    valueField: 'id',
                    store: store_candidat,
                    name: 'tertio_loco'
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
        }/*, {
            xtype: 'ia-history'
        }*/]
    });

    var store_validation_etat = new iafbm.store.CommissionValidationEtat();
    var form_validation = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        store: Ext.create('iafbm.store.CommissionValidation'),
        fetch: {
            model: iafbm.model.CommissionValidation,
            params: { commission_id: <?php echo $d['id'] ?> }
        },
        defaults: {
            anchor: '100%',
            layout: 'hbox',
            combineErrors: true,
            msgTarget: 'under',
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
            fieldLabel: 'Validation par le Décanat',
            items: [{
                xtype: 'ia-datefield',
                name: 'decanat_date',
            }, {
                xtype: 'ia-combo',
                displayField: 'nom',
                valueField: 'id',
                store: store_validation_etat,
                name: 'decanat_etat'
            }, {
                xtype: 'ia-textarea',
                name: 'decanat_commentaire',
                anchor: '100%',
                width: 381,
                growMin: 21,
                grow: true
            }]
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Commentaire DG-CHUV',
            items: [{
                xtype: 'ia-datefield',
                name: 'dg_date'
            }, {
                xtype: 'combo',
                disabled: 'true',
                store: Ext.create('Ext.data.Store', {fields:[], data: []})
            }, {
                xtype: 'ia-textarea',
                name: 'dg_commentaire',
                anchor: '100%',
                width: 381,
                growMin: 21,
                grow: true
            }]
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Validation par le CF',
            items: [{
                xtype: 'ia-datefield',
                name: 'cf_date'
            }, {
                xtype: 'ia-combo',
                displayField: 'nom',
                valueField: 'id',
                store: store_validation_etat,
                name: 'cf_etat'
            }, {
                xtype: 'ia-textarea',
                name: 'cf_commentaire',
                anchor: '100%',
                width: 381,
                growMin: 21,
                grow: true
            }]
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Validation par le CDir',
            items: [{
                xtype: 'ia-datefield',
                name: 'cdir_date'
            }, {
                xtype: 'ia-combo',
                displayField: 'nom',
                valueField: 'id',
                store: store_validation_etat,
                name: 'cdir_etat'
            }, {
                xtype: 'ia-textarea',
                name: 'cdir_commentaire',
                anchor: '100%',
                width: 381,
                growMin: 21,
                grow: true
            }]
        }, {
            baseCls: 'title',
            html: 'Informations complémentaires'
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Réception du rapport',
            items: [{
                xtype: 'ia-datefield',
                name: 'reception_rapport',
            }]
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Proposition de nomination',
            items: [{
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
                        item: new iafbm.form.PropositionNomination({
                            frame: false,
                            //record: me.getRecord(gridView, rowIndex, colIndex, item),
                            //fetch: me.getFetch(gridView, rowIndex, colIndex, item),
                            listeners: {
                                // Closes popup on form save
                                aftersave: function(form, record) {
                                    popup.close();
                                }
                            }
                        })
                    });
                }
            }]
        }, {
            baseCls: 'title',
            html: 'Commentaire'
        }, {
            xtype: 'ia-textarea',
            name: 'commentaire',
            growMin: 21,
            grow: true
        }/*, {
            xtype: 'ia-history'
        }*/]
    });

    var form_finalisation = Ext.create('Ext.ia.form.CommissionPhasePanel', {
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
            labelWidth: 130,
            defaults: {
                margin: '0 3 0 0'
            },
        },
        items: [{
            baseCls: 'title',
            html: 'Finalisation'
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Candidat retenu',
            items: [{
                xtype: 'ia-combo',
                width: 567,
                name: 'candidat_id',
                displayField: '_display',
                valueField: 'id',
                store: new iafbm.store.Candidat({})
            }]
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
            }]
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: "Début d'activité",
            items: [{
                xtype: 'ia-datefield',
                name: 'debut_activite'
            }]
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
            // FIXME: buggy
            listeners: {
                afterrender: function() {
                    var me = this,
                        form = Ext.getCmp('apercu').down('form');
                    if (form.record) {
                        me.disableIf(form.getRecord());
                    }
                    form.on('load', function() {
                        me.disableIf(this.getRecord());
                    });
                }
            },
            disableIf: function(record) {
                this.setDisabled(record.get('commission_etat_id') == 3);
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
        }/*, {
            xtype: 'ia-history'
        }*/]
    });

    var tabPanel = Ext.createWidget('ia-tabpanel-commission', {
        activeTab: 0,
        plain: true,
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
            xtype: 'ia-combo-version',
            tables: [
                'commissions',
                'commissions_membres',
                'commissions_creations',
                'candidats',
                'commissions_candidats',
                'commissions_candidats_commentaires',
                'commissions_travails',
                'commissions_travails_evenements',
                'commissions_validations',
                'commissions_finalisations'
            ],
            getTopLevelComponent: function() {
                return this.up('panel');
            }
        },
            tabPanel
        ]
    });

});

</script>