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
    background-color: #af5;
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
/*
    var store = new Ext.data.Store({
        model: 'Commission',
        proxy: {
            type: 'rest',
            url : '/api/commissions',
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
        pageSize: 10,
        autoLoad: true,
        autoSync: true
    });
*/

    var grid = new Ext.grid.Panel({
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
            pageSize: 10,
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



    var form = Ext.create('Ext.form.Panel', {
        url: '<?php echo u('api/commissions/') ?>',
        method: 'GET',
        reader: {
            type: 'json',
            root: 'items',
            totalProperty: 'xcount',
            model: 'Commission'
        },
        renderTo: 'form',
        title: 'Commission',
        autoHeight: true,
        width: 880,
        bodyPadding: 10,
        defaults: {
            anchor: '100%',
            labelWidth: 100,
            msgTarget: 'side'
        },
        items: [{
            xtype: 'fieldcontainer',
            combineErrors: true,
            layout: 'hbox',
            defaults: {
                flex: 1
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
            //vtype: 'email',
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
            items: [grid]
        }, {
            xtype: 'fieldset',
            title: 'Candidat(s)',
            collapsible: true,
            items: [
                {xtype: 'displayfield', fieldLabel: 'TODO', name: ''}
            ]
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
                        name: 'email',
                    }, {
                        xtype:'datefield',
                        fieldLabel: 'Date de la validation composition par le vice-recteur',
                        name: 'email',
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
                        name: 'email',
                    }, {
                        xtype:'textfield',
                        fieldLabel: 'Terzio loco',
                        name: 'email',
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
                    allowBlank: false
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
                    grow: true,
                    allowBlank: false
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
                    allowBlank: false
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
                    grow: true,
                    allowBlank: false
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
                    allowBlank: false
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
                    grow: true,
                    allowBlank: false
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
                    allowBlank: false
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
                    grow: true,
                    allowBlank: false
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
                    allowBlank: false
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
                    grow: true,
                    allowBlank: false
                }]
            }]
        }],
        buttons: [{
            text: 'Load data',
            handler: function() {
                var form = this.up('form');
                form.load({params:{id:'<?php echo $d['id'] ?>'}});
                aa = form;
            }
        }, {
            text: 'Save',
            handler: function() {
                var form = this.up('form').getForm(),
                    s = '';
                if (form.isValid()) {
                    Ext.iterate(form.getValues(), function(key, value) {
                        s += Ext.util.Format.format("{0} = {1}<br />", key, value);
                    }, this);

                    Ext.Msg.alert('Form Values', s);
                }
            }
        }, {
            text: 'Reset',
            handler: function() {
                this.up('form').getForm().reset();
            }
        }]
    });
});

</script>