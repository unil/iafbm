<h1><?php echo "Evaluation - {$d['personne_prenom']} {$d['personne_nom']}"?></h1>
<div id="target"></div>

<script type="text/javascript">
Ext.onReady(function() {
    
    var trueFalse = Ext.create('Ext.data.Store', {
        fields: ['value', 'name'],
        data : [
            {'value':'1', "name":"Oui"},
            {"value":'0', "name":"Non"},
            {"value":'null', "name":"-"}
        ]
    });
    
    var form_rapportActivite = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        store: Ext.create('iafbm.store.EvaluationRapport'),
        fetch: {
            model: iafbm.model.EvaluationRapport,
            id: <?php echo $d['id'] ?>
        },
        id: "rapportActivite",
        layout: 'column',
        items: [{
            xtype: 'container',
            defaults: {
                labelStyle: 'font-weight:bold',
                labelWidth: '190',
                labelAlign: 'left',
            },
            items: [{
                baseCls: 'title',
                html: 'Suivi du rapport',
                labelWidth: '250'
            },{
                xtype: 'ia-datefield',
                fieldLabel: 'Biblio. demandée le',
                emptyText: 'Biblio. demandée le',
                name: 'date_biblio_demandee',
            },{
                xtype: 'ia-datefield',
                fieldLabel: 'Biblio reçue le',
                emptyText: 'Biblio reçue le',
                name: 'date_biblio_recue',
            },{
                xtype: 'ia-datefield',
                fieldLabel: 'Relancé le',
                name: 'date_relance',
                emptyText: 'Relancé le',
            },{
                xtype: 'ia-datefield',
                fieldLabel: 'Rapport et annexes reçus le',
                emptyText: 'Rapport et annexes reçus le',
                name: 'date_rapport_recu',
            },{
                xtype: 'ia-datefield',
                fieldLabel: 'Transmis à l\'évaluateur le',
                emptyText: 'Transmis à l\'évaluateur le',
                name: 'date_transmis_evaluateur'
            },{
                xtype: 'ia-datefield',
                fieldLabel: 'Date de l\'entretien',
                emptyText: 'Date de l\'entretien',
                name: 'date_entretien'
            },{
                xtype: 'ia-datefield',
                fieldLabel: 'Lettre d\'accusé de réception',
                emptyText: 'Lettre d\'accusé de réception',
                name: 'date_accuse_lettre'
            },{
                xtype: 'ia-datefield',
                fieldLabel: 'E-mail d\'accusé de réception',
                emptyText: 'E-mail d\'accusé de réception',
                name: 'date_accuse_email'
            },{
                xtype: 'ia-textarea',
                fieldLabel: 'Remarques diveres',
                emptyText: 'Remarques diverses',
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
                                xreturn: 'id,nom,prenom,date_naissance,pays.nom AS pays_nom,pays.code AS pays_code'
                            }
                        })
                    },
                    grid: {
                        store: new iafbm.store.EvaluationEvaluateur(),
                        columns: iafbm.columns.Evaluateur
                    },
                    makeData: function(record) {
                        return {
                            personne_id: record.get('id'),
                            evaluation_id: <?php echo $d['id']; ?>,
                            personne_nom: record.get('nom'),
                            personne_prenom: record.get('prenom'),
                            personne_date_naissance: record.get('date_naissance')
                        }
                    }
                }) 
            ]
        }]
    });
    
    var form_evaluation = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        store: Ext.create('iafbm.store.EvaluationEvaluation'),
        fetch: {
            model: iafbm.model.EvaluationEvaluation,
            evaluation_id: <?php echo $d['id'] ?>
        },
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
                name: 'date_rapport_evaluation',
            },{
                xtype: 'ia-combo',
                store: new iafbm.store.EvaluationPreavis(),
                valueField: 'id',
                displayField: 'preavis',
                fieldLabel: 'Préavis Evaluateur',
                name: 'preavis_evaluateur_id',
                editable: false
            },{
                xtype: 'ia-combo',
                store: new iafbm.store.EvaluationPreavis(),
                valueField: 'id',
                displayField: 'preavis',
                fieldLabel: 'Préavis Décanat',
                name: 'preavis_decanat_id',
                editable: false
            },{
                xtype: 'ia-datefield',
                fieldLabel: 'Liste transmise à la Direction de l\'UNIL le',
                emptyText: 'Liste transmis à la Direction de l\'UNIL le',
                name: 'date_liste_transmise'
            },{
                xtype: 'ia-datefield',
                fieldLabel: 'Dossier transmis à la Direction de l\'UNIL le',
                emptyText: 'Dossier transmis à la Direction de l\'UNIL le',
                name: 'date_dossier_transmis'
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
                fieldLabel: 'Remarques diverses',
                emptyText: 'Remarques diverses',
                name: 'commentaire',
                grow: true,
            }]
        }]
    });
    
    var form_cdir = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        store: Ext.create('iafbm.store.EvaluationCdir'),
        fetch: {
            model: iafbm.model.EvaluationCdir,
            id: <?php echo $d['id'] ?>
        },
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
                    store: trueFalse,
                    valueField: 'value',
                    displayField: 'name',
                    fieldLabel: 'Confirmation',
                    name: 'confirmation',
                    editable: false
            },{
                    xtype: 'ia-combo',
                    store: trueFalse,
                    valueField: 'value',
                    displayField: 'name',
                    fieldLabel: 'Renouvellement',
                    name: 'renouvellement',
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
                fieldLabel: 'Remarques diverses',
                emptyText: 'Remarques diverses',
                name: 'commentaire'
            }]
        }]
    });
    
    var form_contrat = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        store: Ext.create('iafbm.store.EvaluationContrat'),
        fetch: {
            model: iafbm.model.EvaluationContrat,
            id: <?php echo $d['id'] ?>
        },
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
                        store: trueFalse,
                        valueField: 'value',
                        displayField: 'name',
                        fieldLabel: 'Copie nouveau contrat reçue',
                        labelWidth: '190',
                        editable: false,
                        name: 'copie_nouveau_contrat'
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
                modelname: 'evaluation',
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