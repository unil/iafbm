<div class="title"><?php echo "N° {$d['id']} - {$d['nom']}" ?></div>

<div id="target"></div>

<script type="text/javascript">

Ext.onReady(function() {

    Ext.QuickTips.init();

    // Shared Candidat store
    var store_candidat = new iafbm.store.Candidat();

    var form_apercu = Ext.create('Ext.ia.form.Panel', {
        store: Ext.create('iafbm.store.Commission'),
        fetch: {
            model: iafbm.model.Commission,
            id: <?php echo $d['id'] ?>
        },
        defaults: {
            flex: 1,
            anchor: '100%'
        },
        items: [{
            xtype: 'fieldcontainer',
            combineErrors: true,
            layout: 'hbox',
            height: 35,
            defaults: { labelStyle: 'font-weight:bold' },
            items: [
                {xtype: 'displayfield', name: 'commission-type_racine', fieldLabel: 'Type', labelWidth: 33, width: 350},
                //{xtype: 'displayfield', name: 'commission-etat_nom', fieldLabel: 'Etat', labelWidth: 27, width: 300},
                {
                    xtype: 'ia-combo',
                    fieldLabel: 'Etat',
                    displayField: 'nom',
                    valueField: 'id',
                    store: Ext.create('iafbm.store.CommissionEtat'),
                    name: 'commission-etat_id',
                    allowBlank: false,
                    labelWidth: 33,
                    width: 180
                },
                //{xtype: 'displayfield', name: 'section_code', fieldLabel: 'Section', labelWidth: 47, width: 100},
                {
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
                },
            ]
        }, {
            xtype: 'textarea',
            name: 'commentaire',
            fieldLabel: 'Commentaire',
        }, new Ext.ia.selectiongrid.Panel({
            title: 'Composition',
            width: 857,
            height: 350,
            plugins: [new Ext.grid.plugin.RowEditing()],
            combo: {
                store: new iafbm.store.Personne(),
            },
            grid: {
                store: new iafbm.store.CommissionMembre(),
                params: {commission_id:<?php echo $d['id'] ?>},
                columns: iafbm.columns.CommissionMembre
            },
            makeData: function(record) {
                return {
                    personne_id: record.get('id'),
                    fonction_id: 1,
                    commission_id: <?php echo $d['id'] ?>,
                    actif: 1
                }
            }
        }), new Ext.ia.ux.grid.History()]
    });


    var form_creation = Ext.create('Ext.ia.form.Panel', {
        store: Ext.create('iafbm.store.CommissionCreation'),
        fetch: {
            model: iafbm.model.CommissionCreation,
            params: { commission_id: <?php echo $d['id'] ?> }
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
            xtype: 'textareafield',
            name: 'commentaire',
            fieldLabel: 'Commentaire',
            width: 858
        }, new Ext.ia.ux.grid.History()]
    });

    var form_candidat = Ext.create('Ext.ia.form.Panel', {
        store: Ext.create('iafbm.store.CommissionCandidatCommentaire'),
        fetch: {
            model: iafbm.model.CommissionCandidatCommentaire,
            params: { commission_id: <?php echo $d['id'] ?> }
        },
        items: [{
            baseCls: 'title',
            html: 'Candidats'
        }, {
            xtype:'ia-editgrid',
            width: 880,
            height: 330,
            store: new iafbm.store.Candidat(),
            columns: iafbm.columns.Candidat,
            pageSize: 10,
            addItem: function() {
                var me = this,
                    popup = new Ext.ia.window.Popup({
                    title: 'Créer un candidat',
                    item: new iafbm.form.Candidat({
                        frame: false,
                        record: new iafbm.model.Candidat({
                            commission_id: <?php echo $d['id'] ?>
                        }),
                        listeners: {
                            aftersave: function(form, record) {
                                me.up('gridpanel').store.load();
                                popup.close();
                            }
                        }

                    })
                });
            },
        }, {
            xtype: 'textareafield',
            name: 'commentaire',
            fieldLabel: 'Commentaire',
            width: 858
        }, new Ext.ia.ux.grid.History()]
    });

    var form_travail = Ext.create('Ext.ia.form.Panel', {
        store: Ext.create('iafbm.store.CommissionTravail'),
        fetch: {
            model: iafbm.model.CommissionTravail,
            params: { commission_id: <?php echo $d['id'] ?> }
        },
        items: [{
            baseCls: 'title',
            html: 'Validation de rapport'
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
/* Draft using Ext.ia.form.field.MultiField:
                items: [{
                    xtype: 'displayfield',
                    value: '<b>Séances annoncées</b>',
                    height: 25
                }, {
                    xtype:'ia-datefield',
                    fieldLabel: "Séance d'évaluation",
                    name: ''
                }, {
                    xtype:'ia-datefield',
                    fieldLabel: 'Journée de visite',
                    name: ''
                }, {
                    xtype:'ia-datefield',
                    fieldLabel: 'Séance de délibération',
                    name: ''
                }]
*/
                items: [{
                    xtype: 'displayfield',
                    value: '<b>Séances annoncées</b>',
                    height: 25
                }, {
                    xtype:'ia-editgrid',
                    frame: false,
                    width: 400,
                    height: 150,
                    bbar: null,
                    store: new iafbm.store.CommissionTravailEvenement({
                        params: { commission_id: '<?php echo $d['id'] ?>' }
                    }),
                    newRecordValues: { commission_id: '<?php echo $d['id'] ?>' },
                    columns: [{
                        header: "Type",
                        dataIndex: 'commission-travail-evenement-type_id',
                        flex: 1,
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
                        flex: 1,
                        xtype: 'ia-datecolumn',
                        field: {
                            xtype: 'ia-datefield',
                            allowBlank: false
                        }
                    },{
                        header: "Procès verbal",
                        dataIndex: 'proces_verbal',
                        flex: 1,
                        xtype: 'checkcolumn',
                        field: {
                            xtype: 'checkbox'
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
        },{
            xtype: 'textareafield',
            name: 'commentaire',
            fieldLabel: 'Commentaire',
            width: 858
        }, new Ext.ia.ux.grid.History()]
    });

    var store_validation_etat = new iafbm.store.CommissionValidationEtat();
    var form_validation = Ext.create('Ext.ia.form.Panel', {
        store: Ext.create('iafbm.store.CommissionValidation'),
        fetch: {
            model: iafbm.model.CommissionValidation,
            params: { commission_id: <?php echo $d['id'] ?> }
        },
        defaults: {
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
                xtype: 'textareafield',
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
                xtype: 'textareafield',
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
                xtype: 'textareafield',
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
                xtype: 'textareafield',
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
                    Ext.Msg.alert('Non disponible', "Cette fonctionnalité n'est pas encore disponible");
                }
            }]
        }, {
            xtype: 'textareafield',
            fieldLabel: 'Commentaire',
            name: 'commentaire',
            labelWidth: 80,
            width: 858
        }, new Ext.ia.ux.grid.History()]
    });

    var form_finalisation = Ext.create('Ext.ia.form.Panel', {
        store: Ext.create('iafbm.store.CommissionFinalisation'),
        fetch: {
            model: iafbm.model.CommissionFinalisation,
            params: { commission_id: <?php echo $d['id'] ?> }
        },
        defaults: {
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
            fieldLabel: 'Réception du contrat',
            items: [{
                xtype: 'ia-datefield',
                name: 'reception_contrat_date',
            }, {
                xtype: 'textareafield',
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
            xtype: 'textareafield',
            fieldLabel: 'Commentaire',
            labelWidth: 80,
            name: 'commentaire',
            anchor: '100%',
            width: 411
        }, new Ext.ia.ux.grid.History()]
    });

    var tabPanel = Ext.createWidget('tabpanel', {
        renderTo: 'target',
        activeTab: 0,
        width: 880,
        plain: true,
        defaults :{
            autoScroll: true,
        },
        items: [{
            id: 'apercu',
            title: 'Apercu général',
            items: form_apercu,
            iconCls: 'tab-icon-ok'
        }, {
            id: 'creation',
            title: 'Phase de création',
            items: form_creation,
            iconCls: 'tab-icon-ok'
        }, {
            id: 'candidat',
            title: 'Candidat',
            items: form_candidat,
            iconCls: 'tab-icon-ok'
        }, {
            id: 'travail',
            title: 'Phase de travail',
            items: form_travail,
            iconCls: 'tab-icon-todo'
        }, {
            id: 'validation',
            title: 'Validation de rapport',
            items: form_validation,
            iconCls: 'tab-icon-todo'
        }, {
            id: 'finalisation',
            title: 'Finalisation',
            items: form_finalisation,
            iconCls: 'tab-icon-todo'
        }],
        listeners: {
            tabchange: function(tabPanel, newCard, oldCard, options) {
                document.location.hash = tabPanel.getActiveTab().id;
            },
            beforerender: function() {
                var tabId = document.location.hash.replace("#", "");
                this.setActiveTab(tabId);
            }
        }
    });

});

</script>