<div id="form"></div>

<style>
.x-fieldset {
    border-bottom: 0 none;
    border-left: 0 none;
    border-right: 0 none;
    padding: 0;
    margin-top: 25px;
    padding-top: 10px;
}
.x-fieldset-header-text {
    font-weight: bold;
}
.x-fieldset-collapsed .x-fieldset-header {
    color: gray;
}
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

    <?php echo xView::load('commissions/extjs/model')->render() ?>
    <?php echo xView::load('personnes/extjs/model')->render() ?>


    /**
     * Grid for commission composition
     */
    var composition_grid = new Ext.grid.Panel({
        id: 'abc-grid',
        loadMask: true,
        width: 857,
        height: 200,
        //frame: true,
        plugins: [new Ext.grid.plugin.RowEditing()],
        store: new Ext.data.Store({
            model: 'Personne',
            proxy: {
                type: 'rest',
                url : '<?php echo u('api/personnes') ?>',
                limitParam: 'xlimit',
                startParam: 'xoffset',
                pageParam: undefined,
                reader: {
                    type: 'json',
                    root: 'items',
                    totalProperty: 'xcount'
                },
                writer: {
                    root: 'items'
                }
            },
            pageSize: null,
            autoLoad: true,
            autoSync: true
        }),
        columns: [{
            header: "Nom",
            dataIndex: 'nom',
            editor: {
                xtype: 'textfield',
                allowBlank: false
            }
        },{
            header: "Prénom",
            dataIndex: 'prenom',
            editor: {
                xtype: 'textfield',
                allowBlank: false
            }
        }],
        dockedItems: [{
            xtype: 'toolbar',
            items: [{
                text: 'Add',
                iconCls: 'icon-add',
                handler: function(){
                    // empty record
                    this.up('gridpanel').store.insert(0, new Personne());
                    rowEditing.startEdit(0, 0);
                }
            }, '-', {
                text: 'Delete',
                iconCls: 'icon-delete',
                handler: function(){
                    var selection = grid.getView().getSelectionModel().getSelection()[0];
                    if (selection) {
                        this.up('gridpanel').store.remove(selection);
                    }
                }
            }]
        }]/*,
        bbar: new Ext.PagingToolbar({
            store: store,
            displayInfo: true,
            displayMsg: 'Eléments {0} à {1} sur {2}',
            emptyMsg: "Pas d'éléments à afficher",
            items:[],
            //plugins: Ext.create('Ext.ux.ProgressBarPager', {})
        })
*/
    });




    /**
     * Grid for commission candidates
     */
    var myData = [
        { name : "Rec 0", column1 : "0", column2 : "0" },
        { name : "Rec 1", column1 : "1", column2 : "1" },
        { name : "Rec 2", column1 : "2", column2 : "2" },
        { name : "Rec 3", column1 : "3", column2 : "3" },
        { name : "Rec 4", column1 : "4", column2 : "4" },
        { name : "Rec 5", column1 : "5", column2 : "5" },
        { name : "Rec 6", column1 : "6", column2 : "6" },
        { name : "Rec 7", column1 : "7", column2 : "7" },
        { name : "Rec 8", column1 : "8", column2 : "8" },
        { name : "Rec 9", column1 : "9", column2 : "9" }
    ];

    var candidates_grid_source = Ext.create('Ext.grid.Panel', {
        viewConfig: {
            plugins: {
                ptype: 'gridviewdragdrop',
                dragGroup: 'firstGridDDGroup',
                dropGroup: 'secondGridDDGroup'
            }
        },
        store: new Ext.data.Store({
            model: 'Personne',
            proxy: {
                type: 'rest',
                url : '<?php echo u('api/personnes') ?>',
                limitParam: 'xlimit',
                startParam: 'xoffset',
                pageParam: undefined,
                reader: {
                    type: 'json',
                    root: 'items',
                    totalProperty: 'xcount'
                },
                writer: {
                    root: 'items'
                }
            },
            pageSize: null,
            autoLoad: true
        }),
        columns: <? echo xView::load('personnes/extjs/columns')->render() ?>,
        stripeRows: true,
        title: 'Disponibles',
        margins: '0 2 0 0',
        listeners: {
            drop: function(node, data, dropRec, dropPosition) {
                var dropOn = dropRec ? ' ' + dropPosition + ' ' + dropRec.get('name') : ' on empty view';
                Ext.example.msg("Drag from left to right", 'Dropped ' + data.records[0].get('name') + dropOn);
            }
        }
    });

    var candidates_grid_destination = Ext.create('Ext.grid.Panel', {
        viewConfig: {
            plugins: {
                ptype: 'gridviewdragdrop',
                dragGroup: 'secondGridDDGroup',
                dropGroup: 'firstGridDDGroup'
            }
        },
        store: Ext.create('Ext.data.Store', {
            model: 'Personne'
        }),
        columns: <? echo xView::load('personnes/extjs/columns')->render() ?>,
        stripeRows: true,
        title: 'Selectionnés',
        margins: '0 0 0 3'
    });

    var candidates_panel = Ext.create('Ext.Panel', {
        flex: 1,
        height: 300,
        border: 0,
        layout: {
            type: 'hbox',
            align: 'stretch'
        },
        defaults: { flex : 1 }, //auto stretch
        items: [
            candidates_grid_source,
            candidates_grid_destination
        ]
    });


    /**
     * Overrides Form.submit() logic: posts json to api url
     */
    Ext.override(Ext.form.Action.Submit, {
        run: function() {
            var json = Ext.encode({
                items: this.form.getValues()
            });
            console.log(json);
/*
            Ext.Ajax.request({
                action: 'update',

            });
*/
        }
    });

    /**
     * Actual form
     */
    var form = Ext.create('Ext.form.Panel', {
        url: '<?php echo u('api/commissions/') ?>',
        method: 'GET',
        reader: new Ext.data.reader.Json({
            root: 'items',
            totalProperty: 'xcount',
            model: 'Commission'
        }),
        renderTo: 'form',
        title: 'Commission',
        autoHeight: true,
        width: 880,
        bodyPadding: 10,
        defaults: {
            //labelWidth: 100,
            //anchor: '100%',
            msgTarget: 'side'
        },
        items: [{
            xtype: 'fieldcontainer',
            combineErrors: true,
            layout: 'hbox',
            defaults: {
                //flex: 1,
                labelAlign: 'right',
            },
            items: [
                {xtype: 'displayfield', fieldLabel: 'N°', name: 'id'},
                {xtype: 'displayfield', fieldLabel: 'Président', name: '...'},
                {xtype: 'displayfield', fieldLabel: 'Candidat', name: '...'},
                {xtype: 'displayfield', fieldLabel: 'Etat', name: 'actif'}
            ]
        }, {
            xtype: 'textarea',
            name: 'description',
            fieldLabel: 'Description',
            anchor: '100%',
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
        }, {
            xtype: 'fieldset',
            title: 'Composition de la commission',
            collapsible: true,
            items: [composition_grid]
        }, {
            xtype: 'fieldset',
            title: 'Candidat(s)',
            collapsible: true,
            items: [candidates_panel]
        }, {
            xtype: 'fieldset',
            title: 'Phase de création',
            collapsible: true,
            items: [{
                xtype: 'fieldcontainer',
                combineErrors: true,
                layout: 'hbox',
                defaults: {
                    border: false,
                    flex: 1
                },
                fieldDefaults: { labelWidth: 200 },
                items: [{
                    items: [{
                        xtype:'datefield',
                        fieldLabel: 'Date de décision du Décanat',
                        name: 'first'
                    }, {
                        xtype:'datefield',
                        fieldLabel: 'Ordre du jour CDir',
                        name: 'company'
                    }, {
                        xtype:'datefield',
                        fieldLabel: 'Autorisation du CDir',
                        name: 'company'
                    }]
                }, {
                    items: [{
                        xtype:'datefield',
                        fieldLabel: 'Annonce journaux OK le',
                        name: 'last'
                    }, {
                        xtype:'datefield',
                        fieldLabel: 'Composition OK le',
                        name: 'email'
                    }, {
                        xtype:'datefield',
                        fieldLabel: 'Date de la validation composition par le vice-recteur',
                        name: 'email'
                    }]
                }]
            }, {
                xtype: 'textareafield',
                name: 'desc',
                width: 835
            }]
        }, {
            xtype: 'fieldset',
            title: 'Phase de travail',
            collapsible: true,
            items: [{
                xtype: 'fieldcontainer',
                combineErrors: true,
                layout: 'hbox',
                defaults: {
                    border: false,
                    flex: 1
                },
                fieldDefaults: { labelWidth: 200 },
                items: [{
                    items: [{
                        xtype: 'displayfield',
                        value: '<b>Séances annoncées</b>',
                    }, {
                        xtype:'datefield',
                        fieldLabel: "Séance d'évaluation",
                        name: 'first'
                    }, {
                        xtype:'datefield',
                        fieldLabel: 'Journée de visite',
                        name: 'company'
                    }, {
                        xtype:'datefield',
                        fieldLabel: 'Séance de délibération',
                        name: 'company'
                    }]
                }, {
                    items: [{
                        xtype: 'displayfield',
                        value: '<b>Choix des candidats</b>',
                    }, {
                        xtype:'textfield',
                        fieldLabel: 'Primo loco',
                        name: 'last'
                    }, {
                        xtype:'textfield',
                        fieldLabel: 'Secondo loco',
                        name: 'email'
                    }, {
                        xtype:'textfield',
                        fieldLabel: 'Terzio loco',
                        name: 'email'
                    }]
                }]
            },{
                xtype: 'textareafield',
                name: 'desc',
                width: 835
            }]
        }, {
            xtype: 'fieldset',
            title: 'Validation du rapport',
            collapsible: true,
            defaults: {
                labelWidth: 200,
                anchor: '100%',
                layout: {
                    type: 'hbox',
                    defaultMargins: {top: 0, right: 5, bottom: 0, left: 0}
                }
            },
            items: [{
                xtype: 'fieldcontainer',
                fieldLabel: 'Validation par le décanat',
                combineErrors: true,
                msgTarget: 'under',
                defaults: {
                    hideLabel: true
                },
                items: [{
                    xtype: 'datefield',
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
                combineErrors: true,
                msgTarget: 'under',
                defaults: {
                    hideLabel: true
                },
                items: [{
                    xtype: 'datefield',
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
                fieldLabel: 'Validation par le CF',
                combineErrors: true,
                msgTarget: 'under',
                defaults: {
                    hideLabel: true
                },
                items: [{
                    xtype: 'datefield',
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
                fieldLabel: 'Validation par le CDir',
                combineErrors: true,
                msgTarget: 'under',
                defaults: {
                    hideLabel: true
                },
                items: [{
                    xtype: 'datefield',
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
                xtype: 'textareafield',
                name: 'desc',
                width: 835
            }]
        }, {
            xtype: 'fieldset',
            title: 'Finalisation',
            collapsible: true,
            defaults: {
                labelWidth: 200,
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
                    xtype: 'datefield',
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
            }]
        }],
        buttons: [{
            text: 'Reset data',
            handler: function() {
                var form = this.up('form');
                form.load({params:{id:'<?php echo $d['id'] ?>'}});
            }
        }, {
            text: 'Save',
            handler: function() {
                var form = this.up('form').getForm();
                if (form.isValid()) form.submit();
            }
        }],
        listeners: {
            afterrender: function() {
                this.load({params:{id:'<?php echo $d['id'] ?>'}});
            }
        },

    });
});

</script>