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

    <?php echo xView::load('commissions/extjs/model')->render() ?>
    <?php echo xView::load('personnes/extjs/model')->render() ?>

    /**
     * Candidates templated combobox
     */
    /*var*/ composition_combo = new Ext.form.field.ComboBox({
        store: <?php echo xView::load('personnes/extjs/store', array('pagesize'=>5))->render() ?>,
        pageSize: 5,
        limitParam: undefined,
        startParam: undefined,
        pageParam: undefined,
        typeAhead: false,
        minChars: 1,
        //hideLabel: true,
        //fieldLabel: 'Rechercher',
        //displayField: 'nom',
        hideTrigger:true,
        width: 350,
        listConfig: {
            loadingText: 'Recherche...',
            emptyText: 'Aucun résultat.',
            // Custom rendering template for each item
            getInnerTpl: function() {
                return [
                    '<div class="ia-search-item">',
                    '<img src="<?php echo u('a/img/icons/trombi_empty.png') ?>"/>',
                    '<h3>{prenom} {nom}</h3>',
                    '<div>{adresse}, {pays_nom}</div>',
                    '<div>{pays_id}, {pays_nom}, {pays_nom_en}, {pays_code}</div>',
                    '<div>{[Ext.Date.format(values.date_naissance, "j M Y")]}</div>',
                    //'<h3><span>{[Ext.Date.format(values.lastPost, "M j, Y")]}<br />by {author}</span>{title}</h3>' +
                    //'{excerpt}' +
                    '</div>'
                ].join('');
            }
        },
        listeners: {
            select: function(combo, selection) {
                // Inserts record into grid store
                this.up('gridpanel').store.insert(0, selection);
                this.clearValue();
            }//,
            //focus: function(combo, event) { this.onTriggerClick() }
        }
    });

    /**
     * Grid for commission composition
     */
    var composition_grid = new Ext.grid.Panel({
        id: 'abc-grid',
        loadMask: true,
        width: 857,
        height: 200,
        //frame: true,
        //plugins: [new Ext.grid.plugin.RowEditing({id:'rowediting'})],
        /*store: <?php echo xView::load('personnes/extjs/store', array('autoload'=>true))->render() ?>,*/
        store: Ext.create('Ext.data.Store', {
            model: 'Personne'
        }),
        columns: [{
            header: "Nom",
            dataIndex: 'nom',
            editor: {
                xtype: 'textfield',
                allowBlank: false
            }
        }, {
            header: "Prénom",
            dataIndex: 'prenom',
            editor: {
                xtype: 'textfield',
                allowBlank: false
            }
        }, {
            header: "Fonction",
            dataIndex: '',
            editor: {
                xtype: 'combo',
                allowBlank: false
            }
        }, {
            header: "Département",
            dataIndex: '',
            editor: {
                xtype: 'combo',
                allowBlank: false
            }
        }],
        viewConfig: {
            plugins: {
                ptype: 'gridviewdragdrop',
                dragGroup: 'composition_dd-group',
                dropGroup: 'composition_dd-group'
            }
        },
        tbar: ['Ajouter', composition_combo],
        bbar: [{
            text: 'Supprimer le candidat sélectionné',
            iconCls: 'icon-delete',
            handler: function(){
                var selection = this.up('gridpanel').getView().getSelectionModel().getSelection()[0];
                if (selection) this.up('gridpanel').store.remove(selection);
            }
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
    var candidates_grid_source = Ext.create('Ext.grid.Panel', {
        viewConfig: {
            plugins: {
                ptype: 'gridviewdragdrop',
                dragGroup: 'firstGridDDGroup',
                dropGroup: 'secondGridDDGroup'
            }
        },
        store: <?php echo xView::load('personnes/extjs/store', array('autosync'=>false))->render() ?>,
        columns: <? echo xView::load('personnes/extjs/columns')->render() ?>,
        stripeRows: true,
        title: 'Disponibles',
        margins: '0 2 0 0',
        listeners: {
            drop: function(node, data, dropRec, dropPosition) {
                console.log('drop');
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
            //anchor: '100%',
            msgTarget: 'side'
        },
        fieldDefaults: {
            labelWidth: 80
        },
        items: [{
            xtype: 'fieldcontainer',
            combineErrors: true,
            //layout: 'hbox',
            defaults: {
                flex: 1,
                labelWidth: 60
            },
            items: [
                {xtype: 'displayfield', fieldLabel: 'N°', name: 'id'},
                {xtype: 'displayfield', fieldLabel: 'Type', name: 'commission-type_nom'},
                {xtype: 'displayfield', fieldLabel: 'Etat', name: 'actif'},
                {xtype: 'displayfield', fieldLabel: 'Président', value: 'Prof. I. Stamenovic'},//,name: '...'},
                {xtype: 'displayfield', fieldLabel: 'Candidat', value: 'Dr. Jekyll'},//},
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
                items: [{
                    items: [{
                        xtype:'datefield',
                        fieldLabel: 'Date de décision du Décanat',
                        name: 'first',
                        format: 'd F Y', altFormats: 'd.m.Y|d-m-Y|d m Y',
                    }, {
                        xtype:'datefield',
                        fieldLabel: 'Ordre du jour CDir',
                        name: 'company',
                        format: 'd F Y', altFormats: 'd.m.Y|d-m-Y|d m Y',

                    }, {
                        xtype:'datefield',
                        fieldLabel: 'Autorisation du CDir',
                        name: 'company',
                        format: 'd F Y', altFormats: 'd.m.Y|d-m-Y|d m Y',

                    }]
                }, {
                    items: [{
                        xtype:'datefield',
                        fieldLabel: 'Annonce journaux OK le',
                        name: 'last',
                        format: 'd F Y', altFormats: 'd.m.Y|d-m-Y|d m Y',

                    }, {
                        xtype:'datefield',
                        fieldLabel: 'Composition OK le',
                        name: 'email',
                        format: 'd F Y', altFormats: 'd.m.Y|d-m-Y|d m Y',
                    }, {
                        xtype:'datefield',
                        fieldLabel: 'Date de la validation composition par le vice-recteur',
                        name: 'email',
                        format: 'd F Y', altFormats: 'd.m.Y|d-m-Y|d m Y',
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
                        xtype:'datefield',
                        fieldLabel: "Séance d'évaluation",
                        name: 'first',
                        format: 'd F Y', altFormats: 'd.m.Y|d-m-Y|d m Y',

                    }, {
                        xtype:'datefield',
                        fieldLabel: 'Journée de visite',
                        name: 'company',
                        format: 'd F Y', altFormats: 'd.m.Y|d-m-Y|d m Y',

                    }, {
                        xtype:'datefield',
                        fieldLabel: 'Séance de délibération',
                        name: 'company',
                        format: 'd F Y', altFormats: 'd.m.Y|d-m-Y|d m Y',

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
                    xtype: 'datefield',
                    name: 'date',
                    format: 'd F Y', altFormats: 'd.m.Y|d-m-Y|d m Y',
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
                    format: 'd F Y', altFormats: 'd.m.Y|d-m-Y|d m Y',
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
                    format: 'd F Y', altFormats: 'd.m.Y|d-m-Y|d m Y',
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
                    format: 'd F Y', altFormats: 'd.m.Y|d-m-Y|d m Y',
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
                    xtype: 'datefield',
                    name: 'date',
                    format: 'd F Y', altFormats: 'd.m.Y|d-m-Y|d m Y',
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
            beforerender: function() {
                this.load({params:{id:'<?php echo $d['id'] ?>'}});
            }
        },

    });
});

</script>