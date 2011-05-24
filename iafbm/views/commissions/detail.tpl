<div class="title">N° 12345 - Commission nom</div>
<div id="target"></div>


<style>
.ia-status {
    border-right: 1px dotted gray;
}
.ia-status.done {
    background-color: #df7;
}
.ia-status.todo {
    background-color: #fd5;
}
</style>


<script type="text/javascript">


Ext.onReady(function() {

    Ext.QuickTips.init();

    var store = null; // Until all forms have their store

    var form_apercu = Ext.create('Ext.ia.form.Panel', {
        store: new iafbm.store.Commission(),
        loadParams: {id: <?php echo $d['id'] ?>},
        defaults: {
            flex: 1,
            anchor: '100%'
        },
        items: [{
            xtype: 'fieldcontainer',
            combineErrors: true,
            layout: 'hbox',
            items: [
                {xtype: 'displayfield', name: 'nom', fieldLabel: 'Type', labelWidth: 33, width: 200},
                {xtype: 'displayfield', name: 'actif', fieldLabel: 'Etat', labelWidth: 27, width: 100},
                {xtype: 'displayfield', value: 'Prof. I. Stamenovic', fieldLabel: 'Président', labelWidth: 58, width: 280},
                {xtype: 'displayfield', value: 'Dr. Jekyll', fieldLabel: 'Candidat', labelWidth: 55, width: 280},
            ]
        }, {
            xtype: 'textarea',
            name: 'description',
            fieldLabel: 'Description',
            allowBlank: false
        }, new Ext.ia.selectiongrid.Panel({
            title: 'Composition',
            width: 857,
            height: 200,
            plugins: [new Ext.grid.plugin.CellEditing({clicksToEdit:1})],
            combo: {
                store: new iafbm.store.Personne(),
            },
            grid: {
                store: new iafbm.store.Membre(),
                columns: iafbm.columns.Membre
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
        store: new iafbm.store.CommissionCreation(),
        loadParams: {commission_id: <?php echo $d['id'] ?>},
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
                    fieldLabel: 'Ordre du jour CDir',
                    name: 'ordrejour'
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
        store: store,
        items: [{
            baseCls: 'title',
            html: 'Candidats'
        }, new Ext.ia.selectiongrid.Panel({
            width: 858,
            height: 200,
            margin: '0 0 10 0',
            combo: {
                store: new iafbm.store.Personne(),
            },
            grid: {
                store: new iafbm.store.Candidat(),
                columns: iafbm.columns.Candidat
            },
            makeData: function(record) {
                return {
                    personne_id: record.get('id'),
                    commission_id: <?php echo $d['id'] ?>,
                    actif: 1
                }
            }
        }), {
            xtype: 'textareafield',
            name: 'commentaire',
            fieldLabel: 'Commentaire',
            width: 858
        }, new Ext.ia.ux.grid.History()]
    });

    var form_travail = Ext.create('Ext.ia.form.Panel', {
        store: store,
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
                items: [{
                    xtype: 'displayfield',
                    value: '<b>Séances annoncées</b>',
                    height: 25
                }, {
                    xtype:'ia-datefield',
                    fieldLabel: "Séance d'évaluation",
                    name: 'first'
                }, {
                    xtype:'ia-datefield',
                    fieldLabel: 'Journée de visite',
                    name: 'company'
                }, {
                    xtype:'ia-datefield',
                    fieldLabel: 'Séance de délibération',
                    name: 'company'
                }]
            }, {
                items: [{
                    xtype: 'displayfield',
                    value: '<b>Choix des candidats</b>',
                    height: 25
                },
                new Ext.ia.selectiongrid.Panel({
                    //title: 'Membres',
                    frame: false,
                    width: 427,
                    height: 140,
                    combo: {
                        store: new iafbm.store.Candidat(),
                    },
                    grid: {
                        // FIXME: wrong store (just for demo)
                        //store: Ext.create('Ext.ia.data.Store', {model: 'Membre'}),
                        store: new iafbm.store.Candidat(),
                        columns: iafbm.columns.Candidat
                    },
                    makeData: function(record) {
                        return {
                            personne_id: record.get('id'),
                            fonction_id: 1,
                            commission_id: <?php echo $d['id'] ?>,
                            actif: 1
                        }
                    }
                })
                ]
            }]
        },{
            xtype: 'textareafield',
            name: 'desc',
            fieldLabel: 'Commentaire',
            width: 858
        }, new Ext.ia.ux.grid.History()]
    });

    var form_validation = Ext.create('Ext.ia.form.Panel', {
        store: store,
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
            fieldLabel: 'Validation par le Décanat',
            items: [{
                xtype: 'ia-datefield',
                name: 'date',
            }, {
                xtype: 'combo',
                name: 'etat',
                value: 'mrs',
                mode: 'local',
                triggerAction: 'all',
                forceSelection: true,
                editable: false,
                displayField: 'name',
                valueField: 'value',
                queryMode: 'local',
                store: Ext.create('Ext.data.Store', {
                    fields: ['name', 'value'],
                    data: [
                        {name: 'Oui', value: 2},
                        {name: 'Non', value: 1},
                        {name: 'Pas de décision', value: 0}
                    ]
                })
            }, {
                xtype: 'textareafield',
                name: 'commentaires',
                anchor: '100%',
                width: 381,
                growMin: 21,
                grow: true
            }]
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Validation par la DG-CHUV',
            items: [{
                xtype: 'ia-datefield',
                name: 'date'
            }, {
                xtype: 'combo',
                name: 'etat',
                value: 'mrs',
                mode: 'local',
                triggerAction: 'all',
                forceSelection: true,
                editable: false,
                displayField: 'name',
                valueField: 'value',
                queryMode: 'local',
                store: Ext.create('Ext.data.Store', {
                    fields: ['name', 'value'],
                    data: [
                        {name: 'Oui', value: 2},
                        {name: 'Non', value: 1},
                        {name: 'Pas de décision', value: 0}
                    ]
                })
            }, {
                xtype: 'textareafield',
                name: 'commentaires',
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
                name: 'date'
            }, {
                xtype: 'combo',
                name: 'etat',
                value: 'mrs',
                mode: 'local',
                triggerAction: 'all',
                forceSelection: true,
                editable: false,
                displayField: 'name',
                valueField: 'value',
                queryMode: 'local',
                store: Ext.create('Ext.data.Store', {
                    fields: ['name', 'value'],
                    data: [
                        {name: 'Oui', value: 2},
                        {name: 'Non', value: 1},
                        {name: 'Pas de décision', value: 0}
                    ]
                })
            }, {
                xtype: 'textareafield',
                name: 'commentaires',
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
                name: 'date'
            }, {
                xtype: 'combo',
                name: 'etat',
                value: 'mrs',
                mode: 'local',
                triggerAction: 'all',
                forceSelection: true,
                editable: false,
                displayField: 'name',
                valueField: 'value',
                queryMode: 'local',
                store: Ext.create('Ext.data.Store', {
                    fields: ['name', 'value'],
                    data: [
                        {name: 'Oui', value: 2},
                        {name: 'Non', value: 1},
                        {name: 'Pas de décision', value: 0}
                    ]
                })
            }, {
                xtype: 'textareafield',
                name: 'commentaires',
                anchor: '100%',
                width: 381,
                growMin: 21,
                grow: true
            }]
        }, {
            baseCls: 'title',
            html: 'Documents'
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Réception du rapport',
            items: [{
                xtype: 'ia-datefield',
                name: 'date',
            }, {
                xtype: 'combo',
                name: 'etat',
                value: 'mrs',
                mode: 'local',
                triggerAction: 'all',
                forceSelection: true,
                editable: false,
                displayField: 'name',
                valueField: 'value',
                queryMode: 'local',
                store: Ext.create('Ext.data.Store', {
                    fields: ['name', 'value'],
                    data: [
                        {name: 'Oui', value: 2},
                        {name: 'Non', value: 1}
                    ]
                })
            }]
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Proposition de nomination',
            items: [{
                xtype: 'ia-datefield',
                name: 'date',
            }, {
                xtype: 'button',
                text: 'Télécharger le formulaire',
                iconCls: 'icon-get',
                handler: function() {
                    Ext.Msg.alert('Non disponible', "Cette fonctionnalité n'est pas encore disponible");
                }
            }]
        }, {
            xtype: 'textareafield',
            fieldLabel: 'Commentaire',
            name: 'desc',
            labelWidth: 80,
            width: 858
        }, new Ext.ia.ux.grid.History()]
    });

    var form_finalisation = Ext.create('Ext.ia.form.Panel', {
        store: store,
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
                name: 'date',
            }, {
                xtype: 'combo',
                name: 'etat',
                value: 'mrs',
                mode: 'local',
                triggerAction: 'all',
                forceSelection: true,
                editable: false,
                displayField: 'name',
                valueField: 'value',
                queryMode: 'local',
                store: Ext.create('Ext.data.Store', {
                    fields: ['name', 'value'],
                    data: [
                        {name: 'Oui', value: 2},
                        {name: 'Non', value: 1},
                    ]
                })
            }, {
                xtype: 'textareafield',
                name: 'commentaires',
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
                name: 'date'
            }]
        }, {
            xtype: 'textareafield',
            fieldLabel: 'Commentaire',
            labelWidth: 80,
            name: 'commentaires',
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
            title: 'Apercu général',
            items: form_apercu,
            iconCls: 'tab-icon-ok'
        }, {
            title: 'Phase de création',
            items: form_creation,
            iconCls: 'tab-icon-ok'
        }, {
            title: 'Candidat',
            items: form_candidat,
            iconCls: 'tab-icon-ok'
        }, {
            title: 'Phase de travail',
            items: form_travail,
            iconCls: 'tab-icon-todo'
        }, {
            title: 'Validation de rapport',
            items: form_validation,
            iconCls: 'tab-icon-todo'
        }, {
            title: 'Finalisation',
            items: form_finalisation,
            iconCls: 'tab-icon-todo'
        }]
    });

});

</script>