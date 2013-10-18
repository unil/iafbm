<h1>
    <?php
    printf('Évaluation %s - %s %s',
        strtolower($d['evaluation_type_type']),
        $d['personne_prenom'],
        $d['personne_nom'])
    ?>
</h1>
<div id="target"></div>

<script type="text/javascript">
Ext.onReady(function() {
    
    var decisionStore = null;
    
    var trueFalse = Ext.create('Ext.data.Store', {
        fields: ['value', 'name'],
        data : [
            {'value':'1', "name":"Oui"},
            {"value":'0', "name":"Non"},
            {"value":'null', "name":"-"}
        ]
    });
    
    // Tableau des valeurs des champs à cacher en fonction du type_id de l'évaluation
    // relatif au champs "id" de la table "activite" ou du champs "activite_id" de iafbm.store.Evaluation
    var typeId_Po           = Array(1,2,3),
        typeId_PoAdPersonam = Array(4,5,6),
        typeId_Pas          = Array(13,14,15),
        typeId_PasAdPersonam= Array(10,11,12),
        typeId_Ptit         = Array(31,32,33),
        typeId_Mer1Ssf      = Array(43),
        typeId_Mer1Ssc      = Array(44,45),
        typeId_Mer2Ssf      = Array(49),
        typeId_Mer2Ssc      = Array(50,51),
        typeId_Pd           = Array(64,65,66),
        typeId_Grp_PoPas    = [].concat(typeId_Po, typeId_PoAdPersonam, typeId_Pas, typeId_PasAdPersonam);
        

    /*
     * Adding lock field feature when form is archived.
     */
    Ext.define('Ext.ia.tab.Panel',{
        extend: 'Ext.ia.tab.CommissionPanel',
        alias: 'widget.ia-tabpanel',       
        dockedItems: [{
            xtype: 'toolbar',
            dock: 'top',
            items: {
                xtype: 'panel',
                bodyStyle: {
                    padding: '15px',
                    background: '#dfd',
                    color: '#030',
                    'text-align': 'center',
                },
                hidden: true,
                listeners: {afterRender: function() {
                    // Variables init
                    me = this,
                    toolbar = me.up(),
                    tabpanel = toolbar.up(),
                    modelName = tabpanel.modelName,
                    fieldModelName = modelName + '_etat_id',
                    formFields = new Array();
                    // Search every field form to found the field {model}_etat_id, like evaluation_etat_id
                    // and display the warning                   
                    me.body.dom.innerHTML = "<b>Cette " + modelName + " est clôturée et ne peut être modifiée</b>";
                    tabpanel.items.each(function(el){
                        //get form
                        var form = el.down('form');
                        
                        //get all fields from the form
                        var f = form.getForm().getFields();
                        f.each(function(a){
                            formFields.push(a);
                        });
                        // TODO: Effectué plusieurs fois, certains à double, tester avec des console.log(form)
                        form.on('load', function() {
                            fields = form.getValues();
                            if (fields['evaluation_evaluation_etat_id'] == 4){// 4 means clôturé
                                Ext.each(formFields, function(c){
                                    c.setReadOnly(true);
                                });
                                me.show();
                            }
                        });
                    });
                }},
            }
        }]
    });
    
    var form_apercuGeneral = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        store: Ext.create('iafbm.store.EvaluationApercu'),
        fetch: {
            model: iafbm.model.EvaluationApercu,
            params: { evaluation_id:<?php echo $d['id'] ?> }
        },
        id: "apercuGeneral",
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
                html: 'Aperçu Général',
                labelWidth: '250'
            },{
                xtype: 'ia-combo',
                store: new iafbm.store.EvaluationEtat(),
                valueField: 'id',
                displayField: 'etat',
                fieldLabel: 'État',
                readOnly: true,
                name: 'evaluation_evaluation_etat_id',
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
                    width: 480,
                    height: 250,
                    id: 'evaluateur-gridpanel',
                    //editable: false,
                    combo: {
                        store: new iafbm.store.Personne({
                            params: {
                                xjoin: 'pays',
                                xreturn: 'id,nom,prenom,date_naissance,pays.nom AS pays_nom,pays.code AS pays_code',
                                xwhere: 'onlyUnchoosedPerson'
                            },
                            listeners: {
                                beforeload: function(s, operation, eOpts) {
                                    var grid = Ext.getCmp('evaluateur-gridpanel'),
                                        idsToAvoid = Array();
                                    Ext.each(grid.store.data.items,function(name){
                                        idsToAvoid.push(name.data.personne_id);
                                    });
                                    s.params.idsToAvoid = idsToAvoid.join();
                                }
                            }
                        })
                    },
                    grid: {
                        store: new iafbm.store.EvaluationEvaluateur({
                            params: { evaluation_id: <?php echo $d['id'] ?> }
                        }),
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
    
    
    var form_rapportActivite = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        store: Ext.create('iafbm.store.EvaluationRapport'),
        fetch: {
            model: iafbm.model.EvaluationRapport,
            params: { evaluation_id:<?php echo $d['id'] ?> }
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
                iaDisableFor: [].concat(typeId_Mer1Ssc, typeId_Mer2Ssc)
            },{
                xtype: 'ia-datefield',
                fieldLabel: 'Biblio reçue le',
                emptyText: 'Biblio reçue le',
                name: 'date_biblio_recue',
                iaDisableFor: [].concat(typeId_Mer1Ssc, typeId_Mer2Ssc)
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
                name: 'date_accuse_lettre',
                iaDisableFor: [].concat(typeId_Mer1Ssc, typeId_Mer2Ssc, typeId_Pd)
            },{
                xtype: 'ia-datefield',
                fieldLabel: 'E-mail d\'accusé de réception',
                emptyText: 'E-mail d\'accusé de réception',
                name: 'date_accuse_email',
                iaDisableFor: [].concat(typeId_Grp_PoPas, typeId_Ptit, typeId_Mer1Ssf, typeId_Mer2Ssf)
            },{
                xtype: 'ia-textarea',
                fieldLabel: 'Remarques diveres',
                emptyText: 'Remarques diverses',
                name: 'commentaire',
                grow: true,
            },{
                xtype: 'textfield',
                name: 'evaluation_etat_id',
                hidden: true
            }]
        }]
    });
    
    var form_evaluation = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        store: Ext.create('iafbm.store.EvaluationEvaluation'),
        id: "formEvaluation",
        fetch: {
            model: iafbm.model.EvaluationEvaluation,
            params: {
                evaluation_id:<?php echo $d['id'] ?>,
                xjoin: 'evaluation'
            }
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
                iaDisableFor: [].concat(typeId_Mer1Ssc, typeId_Mer2Ssc, typeId_Pd)
            },{
                xtype: 'ia-combo',
                store: makeDecisionStore(),
                valueField: 'id',
                displayField: 'decision',
                fieldLabel: 'Préavis Evaluateur',
                name: 'preavis_evaluateur_id',
                editable: false,
                iaDisableFor: [].concat(typeId_Grp_PoPas, typeId_Ptit, typeId_Mer1Ssf, typeId_Mer2Ssf)
            },{
                xtype: 'ia-combo',
                store: makeDecisionStore(),
                valueField: 'id',
                displayField: 'decision',
                fieldLabel: 'Préavis Décanat',
                name: 'preavis_decanat_id',
                editable: false
            },{
                xtype: 'ia-datefield',
                fieldLabel: 'Liste transmise à la Direction de l\'UNIL le',
                emptyText: 'Liste transmis à la Direction de l\'UNIL le',
                name: 'date_liste_transmise',
                iaDisableFor: [].concat(typeId_Grp_PoPas, typeId_Ptit, typeId_Mer1Ssf, typeId_Mer2Ssf)
            },{
                xtype: 'ia-datefield',
                fieldLabel: 'Dossier transmis à la Direction de l\'UNIL le',
                emptyText: 'Dossier transmis à la Direction de l\'UNIL le',
                name: 'date_dossier_transmis',
                iaDisableFor: [].concat(typeId_Mer1Ssc, typeId_Mer2Ssc, typeId_Pd)
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
        }],
    });
    
    /*
     * Kind of Singleton to return the same store.
     */
    function makeDecisionStore() {
        var result = null;
        
        if(decisionStore == null){
            var result = new iafbm.store.EvaluationDecision({
                listeners: {
                    /*
                     * Getting the corrects decisions in function of
                     * the type of the evaluation. (Régulière)
                     */
                    beforeload: function() {
                        // Getting the loaded store
                        var s = Ext.getCmp('apercuGeneral').store;
                        if(!s.loaded){
                            s.load({
                                params: {
                                    evaluation_id:<?php echo $d['id'] ?>
                                }
                            });
                        }
                        
                        //load EvaluationDecision store with correct ids
                        var me = this;
                        s.on('load', function(record) {
                            var evaluation_type_id = record.getAt(0).data.evaluation_evaluation_type_id;
                            switch(evaluation_type_id){
                                case 2: //Probatoire
                                    me.params = {
                                        "id[]": Array(1,4,5,6)
                                    };
                                    break;
                                case 1: //Réguilière
                                    me.params = {
                                        "id[]": Array(1,2,3)
                                    };
                                    break;
                            }
                            if(!me.reloadedOnce || me.reloadedOnce == undefined){
                                me.load();
                                me.reloadedOnce = true;
                            }
                        });
                    }
                }
            });
            decisionStore = result;
        }else{
            result = decisionStore;
        }
        
        return result;
    }
    
    
    var form_cdir = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        store: Ext.create('iafbm.store.EvaluationCdir'),
        id: "formCdir",
        fetch: {
            model: iafbm.model.EvaluationCdir,
            params: { evaluation_id:<?php echo $d['id'] ?> }
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
                    html: 'Cdir',
                    id: 'fieldset-title-cdir'
                },{
                    xtype: 'ia-datefield',
                    id: 'field-cdir-seance',
                    fieldLabel: 'Séance du CDir du',
                    emptyText: 'Séance du CDir du',
                    name: 'seance_cdir',
                    iaDisableFor: typeId_Pd
                },{
                    xtype: 'ia-combo',
                    store: makeDecisionStore(),
                    valueField: 'id',
                    displayField: 'decision',
                    fieldLabel: 'Décision',
                    name: 'decision_id',
                    editable: false,
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
        }],
        /*
         *
         * Renomme l'onglet "Cdir" en "Décision de la Direction de l'UNIL"
         * Renomme le champ "Séance Cdir du" en "Sécance Direction du"
         * Renomme le titre du formulaire (fieldSet) en Décision de la Direction de l'UNIL
         *
         * Thank you Damien !
         */
        renameFields: function() {
            var s = new iafbm.store.Evaluation({params:{id:<?php echo $d['id'] ?>}});
            s.on('load', function(record) {
                // Fetches activite_id
                var activite_id = record.getAt(0).get('activite_id');
                // Determines if fields have to be renamed
                var arrayToRename = [].concat(typeId_Mer1Ssf, typeId_Mer1Ssc, typeId_Mer2Ssf, typeId_Mer2Ssc, typeId_Pd);
                var needToRename = Ext.Array.contains(arrayToRename, activite_id);
                // Renames tab-title and fields-labels if applicable
                if (needToRename) {
                    // Renames tab title
                    Ext.getCmp('cdir').setTitle("Décision de la Direction de l'UNIL");
                    // Renames fields labels
                    var rename = function() {
                        Ext.getCmp('fieldset-title-cdir').el.dom.innerText = "Décision de la Direction de l'UNIL";
                        Ext.getCmp('field-cdir-seance').labelEl.update('Séance Direction du');
                    }
                    // Depending on whether the tab is already rendered or not,
                    // the rename() function is called directly or through the 'aterrender' event
                    if (Ext.getCmp('formCdir').rendered) rename();
                    else Ext.getCmp('formCdir').on('afterrender', rename);
                }                    
            });
            s.load();
        }
    });
    
    var form_contrat = Ext.create('Ext.ia.form.CommissionPhasePanel', {
        store: Ext.create('iafbm.store.EvaluationContrat'),
        fetch: {
            model: iafbm.model.EvaluationContrat,
            params: { evaluation_id:<?php echo $d['id'] ?> }
        },
        layout: 'fit',
        id: 'form_contrat',
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
                listeners: {
                    afterrender: function() {
                        var me = this,
                            form = Ext.getCmp('tabPanelEvaluation').down('form');
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
                    var enable = record.get('evaluation_evaluation_etat_id')!=4 && !versioned;
                    this.setDisabled(!enable);
                },
                // Click logic
                handler: function() {
                    var me = this;
                    Ext.Msg.confirm(
                        'Clôturer l\'évaluation',
                        'Une fois clôturée, l\'évaluation ne peut plus être modifiée. \
                        Cette action est irreversible. <br/><br/> \
                        Voulez-vous clôturer l\'évaluation ?',
                        function(is) {
                            if (is=='yes') me.archiveEvaluation()
                        }
                    );
                },
                archiveEvaluation: function() {
                    var form = Ext.getCmp('tabPanelEvaluation').down('form'),
                        record = form.getRecord();
                    record.set('evaluation_evaluation_etat_id', 0);
                    // Affect evaluations_apercus->post() controller
                    record.save();
                    form.loadRecord();
                }
            }]
        }]
    });
    

    // Panels ids are used for URL hash
    tabPanel = Ext.createWidget('ia-tabpanel', {
        id: 'tabPanelEvaluation',
        modelName: 'evaluation',
        activeTab: 0,
        plain: true,
        type_id: 'evaluation_activite_id', // Name of field containing the activite_id @see Ext.ia.tab.CommissionPanel initComponent
        defaults: {
            autoScroll: true,
        },
        items: [{
                id: 'apercu_general',
                title: 'Aperçu Général',
                items: form_apercuGeneral,
                iconCls: 'tab-icon-unknown'
            },{
                id: 'rapport_activite',
                title: 'Rapport d\'activité',
                items: form_rapportActivite,
                iconCls: 'tab-icon-unknown'
            },{
                id: 'evaluation_tab',
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
        listeners: {
            tabchange: function(tabPanel, newCard, oldCard, options) {
                // Automatic url hash (#) update on tab selection
                document.location.hash = tabPanel.getActiveTab().id;
            },
            beforerender: function() {
                // Automatic tab selection according url hash (#)
                var tabId = document.location.hash.replace("#", "");
                this.setActiveTab(tabId);
                Ext.getCmp('formCdir').renameFields();
            }
        }
    });
    
    var panel = Ext.createWidget('panel', {
        renderTo: 'target',
        id: 'grid',
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