<h1><?php echo "Evaluation n° {$d['id']}"?></h1>

<div id="target"></div>

<script type="text/javascript">
Ext.onReady(function() {
    
    var states = Ext.create('Ext.data.Store', {
    fields: ['abbr', 'name'],
    data : [
        {"abbr":"AL", "name":"Alabama"},
        {"abbr":"AK", "name":"Alaska"},
        {"abbr":"AZ", "name":"Arizona"}
        //...
    ]
    });
    
    var form_rapportActivite = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        /*store: Ext.create('iafbm.store.EvaluationRapport'),
        fetch: {
            model: iafbm.model.EvaluationRapport,
            id: <?php echo $d['id'] ?>
        },*/
        id: "toto",
        defaults: {
            anchor: '100%'
        },
        items: [{
                xtype: 'fieldcontainer',
                combineErrors: true,
                defaults: { labelStyle: 'font-weight:bold' },
                items: [{
                        xtype: 'ia-datefield',
                        fieldLabel: 'Relancé le',
                        emptyText: 'Relancé le',
                        name: 'relance_le',
                        id: 'relance',
                        labelWidth: '175'
                    },{
                        xtype: 'ia-datefield',
                        fieldLabel: 'Rapport reçu le',
                        emptyText: 'Rapport reçu le',
                        name: 'rapport_recu_le',
                        id: 'rapport_recu',
                        labelWidth: '175'
                    },{
                        xtype: 'ia-datefield',
                        fieldLabel: 'Demande bibliométrique le',
                        emptyText: 'Demande bibliométrique le',
                        name: 'demande_bibliometrique_le',
                        id: 'bibliometrie',
                        labelWidth: '175'
                    },{
                        xtype: 'ia-datefield',
                        fieldLabel: 'Transmis à l\'évaluateur le',
                        emptyText: 'Transmis à l\'évaluateur le',
                        name: 'transmis_le',
                        id: 'transmis_evaluateurs',
                        labelWidth: '175'
                    },{
                        xtype: 'ia-combo',
                        store: new iafbm.store.Personne(),
                        //queryMode: 'remote',
                        //queryParam: 'xquery',
                        valueField: 'id',
                        displayField: 'nomPrenom',
                        fieldLabel: 'Evaluateur',
                        emptyText: 'Evaluateur',
                        name: 'evaluateur1',
                        minChars: 1,
                        labelWidth: '175',
                        width: '500',
                        matchFieldWidth: '500',
                        columnWidth: '500',
                        minWidth: '500',
                        typeAhead: true,
                        hideTrigger:true,
                        anchor: '100%',
                        listConfig: {
                            loadingText: 'Recherche...',
                            emptyText: 'Aucun résultat.',
                            // Custom rendering template for each item
                            getInnerTpl: function() {
                                var img = x.context.baseuri+'/a/img/icons/trombi_empty.png';
                                return [
                                    '<div>',
                                    '  <img src="'+img+'" style="float:left;height:39px;margin-right:5px"/>',
                                    '  <h3>{prenom} {nom}</h3>',
                                    '  <div>{pays_nom}{[values.pays_nom ? ",":"&nbsp;"]} {pays_code}</div>',
                                    '  <div>{[values.date_naissance ? Ext.Date.format(values.date_naissance, "j M Y") : "&nbsp;"]}</div>',
                                    '</div>'
                                ].join('');
                            }
                        }
                    },{
                        xtype: 'ia-datefield',
                        fieldLabel: 'Date entretien',
                        emptyText: 'Date entretien',
                        name: 'date_entretien',
                        id: 'entretien',
                        labelWidth: '175'
                    },{
                        xtype: 'ia-textarea',
                        fieldLabel: 'Commentaire',
                        emptyText: 'Commentaire',
                        name: 'commentaire',
                        id: 'commentaire',
                        labelWidth: '175'
                    }
                ]
            }
        ]
    });
    
    var form_evaluation = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        /*store: Ext.create('iafbm.store.Commission'),
        fetch: {
            model: iafbm.model.Commission,
            id: <?php echo $d['id'] ?>
        },*/
        defaults: {
            anchor: '100%'
        },
        items: [{
                xtype: 'fieldcontainer',
                combineErrors: true,
                //layout: 'hbox',
                defaults: { labelStyle: 'font-weight:bold' },
                items: [{
                        xtype: 'ia-datefield',
                        fieldLabel: 'Rapport d\'évaluation OK le',
                        emptyText: 'Rapport d\'évaluation OK le',
                        name: 'rapport_evaluation_ok',
                        labelWidth: '275'
                    },{
                        xtype: 'ia-combo',
                        store: states,
                        valueField: 'id',
                        displayField: 'name',
                        fieldLabel: 'Préavis évaluateur',
                        name: 'preavis_evaluateur',
                        labelWidth: '275'
                    },{
                        xtype: 'ia-combo',
                        store: states,
                        valueField: 'id',
                        displayField: 'name',
                        fieldLabel: 'Préavis Décanat',
                        name: 'preavis_decanat',
                        labelWidth: '275'
                    },{
                        xtype: 'ia-datefield',
                        fieldLabel: 'Dossier transmis à la Direction de l\'UNIL le',
                        emptyText: 'Dossier transmis à la Direction de l\'UNIL le',
                        name: 'dossier_transmis_direction',
                        labelWidth: '275'
                    },{
                        xtype: 'ia-textarea',
                        fieldLabel: 'Commentaire',
                        emptyText: 'Commentaire',
                        name: 'commentaire',
                        labelWidth: '275'
                    }
                ]
            }
        ]
    });
    
    var form_cdir = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        /*store: Ext.create('iafbm.store.Commission'),
        fetch: {
            model: iafbm.model.Commission,
            id: <?php echo $d['id'] ?>
        },*/
        defaults: {
            anchor: '100%'
        },
        items: [{
                xtype: 'fieldcontainer',
                combineErrors: true,
                //layout: 'hbox',
                defaults: { labelStyle: 'font-weight:bold' },
                items: [{
                        xtype: 'ia-datefield',
                        fieldLabel: 'Séance du CDir du',
                        emptyText: 'Séance du CDir du',
                        name: 'seance_cdir',
                        labelWidth: '175'
                    },{
                        xtype: 'ia-combo',
                        store: states,
                        valueField: 'id',
                        displayField: 'name',
                        fieldLabel: 'Renouvellement',
                        name: 'renouvellement',
                        labelWidth: '175'
                    },{
                        xtype: 'ia-combo',
                        store: states,
                        valueField: 'id',
                        displayField: 'name',
                        fieldLabel: 'Confirmation',
                        name: 'confirmation',
                        labelWidth: '175'
                    },{
                        xtype: 'ia-textarea',
                        fieldLabel: 'Commentaire',
                        emptyText: 'Commentaire',
                        name: 'commentaire',
                        labelWidth: '175'
                    }
                ]
            }
        ]
    });
    
    var form_contrat = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        /*store: Ext.create('iafbm.store.Commission'),
        fetch: {
            model: iafbm.model.Commission,
            id: <?php echo $d['id'] ?>
        },*/
        defaults: {
            anchor: '100%'
        },
        items: [{
                xtype: 'fieldcontainer',
                combineErrors: true,
                //layout: 'hbox',
                defaults: { labelStyle: 'font-weight:bold' },
                items: [{
                        xtype: 'ia-combo',
                        store: states,
                        valueField: 'id',
                        displayField: 'name',
                        fieldLabel: 'Prolongation contrat reçue',
                        //name: 'prolongation_contrat',
                        labelWidth: '175'
                    },{
                        xtype: 'ia-textarea',
                        fieldLabel: 'Commentaire',
                        emptyText: 'Commentaire',
                        name: 'commentaire',
                        labelWidth: '175'
                    }
                ]
            }
        ]
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