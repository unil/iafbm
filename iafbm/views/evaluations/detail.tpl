<h1><?php echo "Evaluation n° {$d['id']}"?></h1>

<div id="target"></div>

<script type="text/javascript">
Ext.onReady(function() {
    
    var preavis = Ext.create('Ext.data.Store', {
    fields: ['abbr', 'name'],
    data : [
        {"abbr":"AL", "name":"Renouveler"},
        {"abbr":"AK", "name":"Ne pas renouveler"},
        {"abbr":"AL", "name":"Confirmer"},
        {"abbr":"AK", "name":"Ne pas confirmer"}
    ]
    });
    
    var ouiNon = Ext.create('Ext.data.Store', {
    fields: ['abbr', 'name'],
    data : [
        {"abbr":"Y", "name":"Oui"},
        {"abbr":"N", "name":"Non"},
    ]
    });
    
    var form_rapportActivite = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        /*store: Ext.create('iafbm.store.EvaluationRapport'),
        fetch: {
            model: iafbm.model.EvaluationRapport,
            id: <?php echo $d['id'] ?>
        },*/
        id: "rapportActivite",
        layout: 'column',
        items: [{
            xtype: 'container',
            defaults: {
                labelStyle: 'font-weight:bold',
                labelWidth: '165',
                labelAlign: 'left',
            },
            items: [{
                baseCls: 'title',
                html: 'Suivi du rapport',
                labelWidth: '250'
            },{
                xtype: 'fieldcontainer',
                fieldLabel: 'Relancé le',
                layout: 'column',
                margin: '0',
                items: [{
                    xtype: 'ia-datefield',
                    name: 'relance_le',
                    emptyText: 'Relancé le',
                },{
                    xtype: 'button',
                    text: 'Relancer',
                    margin: '0 0 0 10',
                    iconCls: 'icon-email',
                }]
            },{
                xtype: 'ia-datefield',
                fieldLabel: 'Rapport reçu le',
                emptyText: 'Rapport reçu le',
                name: 'rapport_recu',
            },{
                xtype: 'ia-datefield',
                fieldLabel: 'Transmis à l\'évaluateur le',
                emptyText: 'Transmis à l\'évaluateur le',
                name: 'transmis_evaluateur'
            },{
                xtype: 'ia-datefield',
                fieldLabel: 'Date de l\'entretien',
                emptyText: 'Date de l\'entretien',
                name: 'date_entretien'
            },{
                xtype: 'ia-textarea',
                fieldLabel: 'Commentaire',
                emptyText: 'Commentaire',
                name: 'commentaire',
                grow: true,
            }]
        },{
            xtype: 'container',
            margin: '0 0 0 20',
            items: [{
                    baseCls: 'title',
                    html: 'Evaluateurs'
                },new Ext.ia.selectiongrid.Panel({
                    //title: 'Membres nominatifs',
                    width: 480,
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
                        store: new iafbm.store.Personne(),
                        columns: iafbm.columns.Candidat
                    }/*,
                    makeData: function(record) {
                        return {
                            personne_id: record.get('id'),
                            commission_fonction_id: 1,
                            commission_id: 1,
                            personne_nom: record.get('nom'),
                            personne_prenom: record.get('prenom')
                        }
                    }*/
                }) 
            ]
        }]
    });
    
    var form_evaluation = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        /*store: Ext.create('iafbm.store.EvaluationRapport'),
        fetch: {
            model: iafbm.model.EvaluationRapport,
            id: <?php echo $d['id'] ?>
        },*/
        layout: 'column',
        items: [{
            xtype: 'container',
            defaults: {
                labelStyle: 'font-weight:bold',
                labelWidth: '275',
                labelAlign: 'left',
            },
            items: [{
                baseCls: 'title',
                html: 'Evaluation',
            },{
                xtype: 'ia-datefield',
                fieldLabel: 'Rapport d\'évaluation - OJ Décanat du',
                emptyText: 'Rapport d\'évaluation - OJ Décanat du',
                name: 'rapport_recu',
            },{
                xtype: 'ia-combo',
                store: preavis,
                valueField: 'id',
                displayField: 'name',
                fieldLabel: 'Préavis Décanat',
                name: 'preavis_decanat',
                editable: false
            },{
                xtype: 'ia-combo',
                store: preavis,
                valueField: 'id',
                displayField: 'name',
                fieldLabel: 'Dossier transmis à la Direction de l\'UNIL le',
                name: 'preavis_decanat',
                editable: false
            }]
        },{
            xtype: 'container',
            margin: '20 0 0 20',
            defaults: {
                labelStyle: 'font-weight:bold',
                labelWidth: '100',
                labelAlign: 'left',
            },
            items: [{
                xtype: 'ia-textarea',
                fieldLabel: 'Commentaire',
                emptyText: 'Commentaire',
                name: 'commentaire',
                grow: true,
            }]
        }]
    });
    
    var form_cdir = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        /*store: Ext.create('iafbm.store.Commission'),
        fetch: {
            model: iafbm.model.Commission,
            id: <?php echo $d['id'] ?>
        },*/
        layout: 'column',
        items: [{
            xtype: 'container',
            defaults: {
                labelStyle: 'font-weight:bold',
                labelWidth: '150',
                labelAlign: 'left',
            },
            items: [{
                    baseCls: 'title',
                    html: 'Cdir'
                },{
                    xtype: 'ia-datefield',
                    fieldLabel: 'Séance du CDir du',
                    emptyText: 'Séance du CDir du',
                    name: 'seance_cdir',
                },{
                    xtype: 'ia-combo',
                    store: ouiNon,
                    valueField: 'id',
                    displayField: 'name',
                    fieldLabel: 'Renouvellement',
                    name: 'renouvellement',
                    editable: false
                },{
                    xtype: 'ia-combo',
                    store: ouiNon,
                    valueField: 'id',
                    displayField: 'name',
                    fieldLabel: 'Confirmation',
                    name: 'confirmation',
                    editable: false
            }]
        },{
            xtype: 'container',
            defaults: {
                labelStyle: 'font-weight:bold',
                labelWidth: '100',
                labelAlign: 'left',
            },
            margin: '20 0 0 40',
            items: [{
                xtype: 'ia-textarea',
                fieldLabel: 'Commentaire',
                emptyText: 'Commentaire',
                name: 'commentaire',
            }]
        }]
    });
    
    var form_contrat = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        /*store: Ext.create('iafbm.store.Commission'),
        fetch: {
            model: iafbm.model.Commission,
            id: <?php echo $d['id'] ?>
        },*/
        layout: 'fit',
        items: [{
            xtype: 'container',
            layout: 'column',
            items: [{
                xtype: 'container',
                defaults: {
                    labelStyle: 'font-weight:bold',
                    labelWidth: '100',
                    labelAlign: 'left',
                },
                items: [{
                        baseCls: 'title',
                        html: 'Contrat'
                    },{
                        xtype: 'ia-combo',
                        store: ouiNon,
                        valueField: 'id',
                        displayField: 'name',
                        fieldLabel: 'Copie nouveau contrat reçue',
                        labelWidth: '190',
                        editable: false
                    }]
            },{
                xtype: 'container',
                defaults: {
                    labelStyle: 'font-weight:bold',
                    labelWidth: '100',
                    labelAlign: 'left',
                },
                margin: '35 0 0 40',
                items: [{
                        xtype: 'ia-textarea',
                        fieldLabel: 'Commentaire',
                        emptyText: 'Commentaire',
                        name: 'commentaire',
                }]
            }]
        },{
            xtype: 'container',
            items: [{
                xtype: 'button',
                text: '<span style="font-weight:bold;font-size:18px">Clôturer</span>',
                height: 50,
                width: 906,
                // Disables button if commission is already 'closed'
                /*listeners: {
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
                }*/
            }]
        }]
    });
    

    // Panels ids are used for URL hash
    var tabPanel = Ext.createWidget('ia-tabpanel-commission', {
        activeTab: 0,
        plain: true,
        defaults: {
            autoScroll: true,
        },
        items: [{
                id: 'rapport_activite',
                title: 'Rapport d\'activité',
                items: form_rapportActivite,
                iconCls: 'tab-icon-unknown'
            },{
                id: 'evaluation',
                title: 'Evaluation',
                items: form_evaluation,
                iconCls: 'tab-icon-unknown'
            },{
                id: 'cdir',
                title: 'CDir',
                items: form_cdir,
                iconCls: 'tab-icon-unknown'
            },{
                id: 'contrat',
                title: 'Contrat',
                items: form_contrat,
                iconCls: 'tab-icon-unknown'
        }],
        /*listeners: {
            tabchange: function(tabPanel, newCard, oldCard, options) {
                // Automatic url hash (#) update on tab selection
                document.location.hash = tabPanel.getActiveTab().id;
            },
            beforerender: function() {
                // Automatic tab selection according url hash (#)
                var tabId = document.location.hash.replace("#", "");
                this.setActiveTab(tabId);
            }
        }*/
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