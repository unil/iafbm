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
            labelWidth: 100,
            anchor: '100%'
        },
        items: [{
            xtype: 'fieldcontainer',
            combineErrors: true,
            layout: 'hbox',
            items: [
                {xtype: 'displayfield', fieldLabel: 'N°', name: 'id'},
                {xtype: 'displayfield', fieldLabel: 'Type', name: 'commission-type_nom'},
                {xtype: 'displayfield', fieldLabel: 'Etat', name: 'actif'},
                {xtype: 'displayfield', fieldLabel: 'Président', value: 'Prof. I. Stamenovic'},//,name: '...'},
                {xtype: 'displayfield', fieldLabel: 'Candidat', value: 'Dr. Jekyll'},//,name: '...'},
            ]
        }, {
            xtype: 'textarea',
            name: 'description',
            fieldLabel: 'Description',
            allowBlank: false
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Avancement',
            combineErrors: true,
            layout: 'hbox',
            defaults: {
                flex: 1,
                padding: 5,
                baseCls: 'ia-status'
            },
            items: [
                {xtype: 'displayfield', value: 'Candidat', cls: 'done'},
                {xtype: 'displayfield', value: 'Phase de création', cls: 'done'},
                {xtype: 'displayfield', value: 'Phase de travail', cls: 'done'},
                {xtype: 'displayfield', value: 'Validation de rapport', cls: 'todo'},
                {xtype: 'displayfield', value: 'Finalisation', cls: 'todo'}
            ]
        }, new Ext.ia.selectiongrid.Panel({
            title: 'Composition',
            width: 857,
            height: 200,
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
        })]
    });


    /*var*/ form_creation = Ext.create('Ext.ia.form.Panel', {
        store: new iafbm.store.CommissionCreation(),
        loadParams: {commission_id: <?php echo $d['id'] ?>},
        items: [{
            xtype: 'fieldcontainer',
            combineErrors: true,
            layout: 'hbox',
            defaults: {
                border: false,
                flex: 1
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
                    fieldLabel: 'Date de la validation composition par le vice-recteur',
                    name: 'composition_validation'
                }]
            }]
        }, {
            xtype: 'textareafield',
            name: 'commentaire',
            width: 835
        }]
    });

    var form_candidat = Ext.create('Ext.ia.form.Panel', {
        store: store,
        items: [
            new Ext.ia.selectiongrid.Panel({
                title: 'Candidat(s)',
                width: 857,
                height: 200,
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
            })
        ]
    });

    var form_travail = Ext.create('Ext.ia.form.Panel', {
        store: store,
        items: [{
            xtype: 'fieldcontainer',
            combineErrors: true,
            layout: 'hbox',
            defaults: {
                border: false,
                flex: 1
            },
            items: [{
                items: [{
                    xtype: 'displayfield',
                    value: '<b>Séances annoncées</b>',
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
                },
                new Ext.ia.selectiongrid.Panel({
                    //title: 'Membres',
                    frame: false,
                    width: 404,
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
            width: 835
        }]
    });

    var form_validation = Ext.create('Ext.ia.form.Panel', {
        store: store,
        defaults: {
            layout: 'hbox',
            margin: '0 5 0 0',
            combineErrors: true,
            msgTarget: 'under',
        },
        items: [{
            xtype: 'fieldcontainer',
            fieldLabel: 'Validation par le Décanat',
            defaults: {
                margin: '0 5 0 0'
            },
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
                width: 320,
                growMin: 21,
                grow: true
            }]
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Validation par la DG-CHUV',
            defaults: {
                margin: '0 5 0 0'
            },
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
                width: 320,
                growMin: 21,
                grow: true
            }]
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Validation par le CF',
            defaults: {
                margin: '0 5 0 0'
            },
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
                width: 320,
                growMin: 21,
                grow: true
            }]
        }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Validation par le CDir',
            defaults: {
                margin: '0 5 0 0'
            },
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
                width: 320,
                growMin: 21,
                grow: true
            }]
        }, {
            xtype: 'textareafield',
            fieldLabel: 'Commentaire',
            name: 'desc',
            width: 835
        }]
    });

    var form_finalisation = Ext.create('Ext.ia.form.Panel', {
        store: store,
        items: [{
            xtype: 'fieldset',
            title: 'Finalisation',
            collapsible: true,
            defaults: {
                anchor: '100%',
                layout: {
                    type: 'hbox',
                    defaultMargins: {top: 0, right: 5, bottom: 0, left: 0}
                }
            },
            items: [{
                xtype: 'fieldcontainer',
                fieldLabel: 'Réception du contrat',
                combineErrors: true,
                msgTarget: 'under',
                defaults: {
                    hideLabel: true
                },
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
                    width: 320,
                    growMin: 21,
                    grow: true
                }]
            }]
        }]
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
                items: form_apercu
            },{
                title: 'Phase de création',
                items: form_creation
            },{
                title: 'Candidat',
                items: form_candidat
            },{
                title: 'Phase de travail',
                items: form_travail
            },{
                title: 'Validation de rapport',
                items: form_validation
            },{
                title: 'Finalisation',
                items: form_finalisation
            }
        ]
    });

});

</script>