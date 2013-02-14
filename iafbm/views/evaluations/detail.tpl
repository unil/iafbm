<h1><?php echo "Commission n° {$d['id']} - {$d['nom']}" ?></h1>

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
                defaults: { labelStyle: 'font-weight:bold' },
                items: [{
                        xtype: 'ia-datefield',
                        fieldLabel: 'Relancé le',
                        emptyText: 'Relancé le',
                        name: 'relance_le',
                        labelWidth: '175'
                    },{
                        xtype: 'ia-datefield',
                        fieldLabel: 'Rapport reçu le',
                        emptyText: 'Rapport reçu le',
                        name: 'rapport_recu_le',
                        labelWidth: '175'
                    },{
                        xtype: 'ia-datefield',
                        fieldLabel: 'Demande bibliométrique le',
                        emptyText: 'Demande bibliométrique le',
                        name: 'demande_bibliometrique_le',
                        labelWidth: '175'
                    },{
                        xtype: 'ia-combo',
                        store: new iafbm.store.Personne(),
                        valueField: 'id',
                        displayField: 'nom',
                        fieldLabel: 'Evaluateur 1',
                        emptyText: 'Evaluateur 1',
                        name: 'evaluateur1',
                        labelWidth: '175'
                    },{
                        xtype: 'ia-combo',
                        store: states,
                        valueField: 'id',
                        displayField: 'name',
                        fieldLabel: 'Evaluateur 2',
                        emptyText: 'Evaluateur 2',
                        name: 'evaluateur2',
                        labelWidth: '175'
                    },{
                        xtype: 'ia-datefield',
                        fieldLabel: 'Date entretien',
                        emptyText: 'Date entretien',
                        name: 'date_entretien',
                        labelWidth: '175'
                    },{
                        xtype: 'ia-datefield',
                        fieldLabel: 'Transmis à l\'évaluateur le',
                        emptyText: 'Transmis à l\'évaluateur le',
                        name: 'transmis_le',
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
                        labelWidth: '175'
                    },{
                        xtype: 'ia-datefield',
                        fieldLabel: 'Dossier transmis à la Direction de l\'UNIL le',
                        emptyText: 'Dossier transmis à la Direction de l\'UNIL le',
                        name: 'dossier_transmis_direction',
                        labelWidth: '175'
                    },{
                        xtype: 'ia-combo',
                        store: states,
                        valueField: 'id',
                        displayField: 'name',
                        fieldLabel: 'Préavis évaluateur',
                        name: 'preavis_evaluateur',
                        labelWidth: '175'
                    },{
                        xtype: 'ia-combo',
                        store: states,
                        valueField: 'id',
                        displayField: 'name',
                        fieldLabel: 'Préavis Décanat',
                        name: 'preavis_decanat',
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