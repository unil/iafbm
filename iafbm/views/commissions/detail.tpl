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

.ia-search-item {
    border-bottom: 1px dotted lightgray;
}
.ia-search-item img {
    float: left;
    margin-right: 10px;
}

</style>

<script type="text/javascript">

Ext.onReady(function() {

    Ext.QuickTips.init();

    var composition_panel = Ext.create('Ext.Panel', {
        flex: 1,
        height: 300,
        border: 0,
        layout: {
            type: 'hbox',
            align: 'stretch'
        },
        defaults: { flex : 1 }, //auto stretch
        items: [
            new Ext.ia.selectiongrid.Panel({
                title: 'Membres',
                width: 857,
                height: 200,
                margins: '0 5 0 0',
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
            }),
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
            }),
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
            //anchor: '100%',
            msgTarget: 'side'
        },
        fieldDefaults: {
            labelWidth: 80
        },
        items: [{
            xtype: 'fieldcontainer',
            combineErrors: true,
            layout: 'hbox',
            defaults: {
                flex: 1,
                labelWidth: 60
            },
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
            items: [composition_panel]
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
                items: [{
                    items: [{
                        xtype:'ia-datefield',
                        fieldLabel: 'Date de décision du Décanat',
                        name: 'first'
                    }, {
                        xtype:'ia-datefield',
                        fieldLabel: 'Ordre du jour CDir',
                        name: 'company'
                    }, {
                        xtype:'ia-datefield',
                        fieldLabel: 'Autorisation du CDir',
                        name: 'company'
                    }]
                }, {
                    items: [{
                        xtype:'ia-datefield',
                        fieldLabel: 'Annonce journaux OK le',
                        name: 'last'
                    }, {
                        xtype:'ia-datefield',
                        fieldLabel: 'Composition OK le',
                        name: 'email'
                    }, {
                        xtype:'ia-datefield',
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
                anchor: '100%',
                layout: {
                    type: 'hbox',
                    defaultMargins: {top: 0, right: 5, bottom: 0, left: 0}
                }
            },
            items: [{
                xtype: 'fieldcontainer',
                fieldLabel: 'Validation par le Décanat',
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
            }, {
                xtype: 'fieldcontainer',
                fieldLabel: 'Validation par la DG-CHUV',
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
            }, {
                xtype: 'fieldcontainer',
                fieldLabel: 'Validation par le CF',
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
            }, {
                xtype: 'fieldcontainer',
                fieldLabel: 'Validation par le CDir',
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
                // FIXME: see personnes/details form
            }
        }],
        listeners: {
            beforerender: function() {
                this.load({params:{id:'<?php echo $d['id'] ?>'}});
            }
        },

    });
});

</script>