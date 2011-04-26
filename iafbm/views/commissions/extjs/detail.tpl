<div id="form"></div>

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
        width: 880,
        height: 300,
        frame: true,
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



    var defaults_fieldset = {
        labelWidth: 89,
        anchor: '100%',
        layout: {
            type: 'hbox',
            defaultMargins: {top: 0, right: 5, bottom: 0, left: 0}
        }
    };

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
            xtype: 'fieldset',
            title: 'Composition de la commission',
            collapsible: true,
            defaults: defaults_fieldset,
            items: [grid]
        }, {
            xtype: 'fieldset',
            title: 'Candidat(s)',
            collapsible: true,
            defaults: defaults_fieldset,
            items: [
                {xtype: 'displayfield', fieldLabel: 'TODO', name: ''}
            ]
        }, {
            xtype: 'fieldset',
            title: 'Phase de création',
            collapsible: true,
            defaults: defaults_fieldset,
            items: [
                {xtype: 'displayfield', fieldLabel: 'TODO', name: ''}
            ]
        }, {
            xtype: 'fieldset',
            title: 'Phase de travail',
            collapsible: true,
            defaults: defaults_fieldset,
            items: [
                {xtype: 'displayfield', fieldLabel: 'TODO', name: ''}
            ]
        }, {
            xtype: 'fieldset',
            title: 'Validation de rapport',
            collapsible: true,
            defaults: defaults_fieldset,
            items: [
                {xtype: 'displayfield', fieldLabel: 'TODO', name: ''}
            ]
        }, {
            xtype: 'fieldset',
            title: 'Finalisation',
            collapsible: true,
            defaults: defaults_fieldset,
            items: [
                {xtype: 'displayfield', fieldLabel: 'TODO', name: ''}
            ]
        }, {
            xtype: 'fieldset',
            title: 'Details',
            collapsible: true,
            defaults: defaults_fieldset,
            items: [{
                xtype: 'fieldcontainer',
                fieldLabel: 'Phone',
                combineErrors: true,
                msgTarget: 'under',
                defaults: {
                    hideLabel: true
                },
                items: [
                    {xtype: 'displayfield', value: '('},
                    {xtype: 'textfield',    fieldLabel: 'Phone 1', name: 'phone-1', width: 29, allowBlank: false},
                    {xtype: 'displayfield', value: ')'},
                    {xtype: 'textfield',    fieldLabel: 'Phone 2', name: 'phone-2', width: 29, allowBlank: false, margins: '0 5 0 0'},
                    {xtype: 'displayfield', value: '-'},
                    {xtype: 'textfield',    fieldLabel: 'Phone 3', name: 'phone-3', width: 48, allowBlank: false}
                ]
            }, {
                xtype: 'fieldcontainer',
                fieldLabel: 'Time worked',
                combineErrors: false,
                defaults: {
                    hideLabel: true
                },
                items: [{
                        name: 'hours',
                        xtype: 'numberfield',
                        width: 48,
                        allowBlank: false
                    }, {
                        xtype: 'displayfield',
                        value: 'hours'
                    }, {
                        name: 'minutes',
                        xtype: 'numberfield',
                        width: 48,
                        allowBlank: false
                    }, {
                        xtype: 'displayfield',
                        value: 'mins'
                    }]
                }, {
                    xtype: 'fieldcontainer',
                    combineErrors: true,
                    fieldLabel: 'Full Name',
                    defaults: {
                        hideLabel: true
                    },
                    items: [{
                        //the width of this field in the HBox layout is set directly
                        //the other 2 items are given flex: 1, so will share the rest of the space
                        width:          50,

                        xtype:          'combo',
                        mode:           'local',
                        value:          'mrs',
                        triggerAction:  'all',
                        forceSelection: true,
                        editable:       false,
                        fieldLabel:     'Title',
                        name:           'title',
                        displayField:   'name',
                        valueField:     'value',
                        queryMode: 'local',
                        store: Ext.create('Ext.data.Store', {
                            fields: ['name', 'value'],
                            data: [
                                {name : 'Mr',   value: 'mr'},
                                {name : 'Mrs',  value: 'mrs'},
                                {name : 'Miss', value: 'miss'}
                            ]
                        })
                    }, {
                        xtype: 'textfield',
                        flex: 1,
                        name: 'firstName',
                        fieldLabel: 'First',
                        allowBlank: false
                    }, {
                        xtype: 'textfield',
                        flex: 1,
                        name: 'lastName',
                        fieldLabel: 'Last',
                        allowBlank: false,
                        margins: '0'
                    }
                ]
            }, {
            xtype: 'fieldcontainer',
            fieldLabel: 'Date Range',
            combineErrors: true,
            layout: 'hbox',
            defaults: {
                flex: 1,
                hideLabel: true
            },
            items: [{
                xtype: 'datefield',
                name: 'startDate',
                fieldLabel: 'Start',
                margin: '0 5 0 0',
                allowBlank: false
            }, {
                xtype: 'datefield',
                name: 'endDate',
                fieldLabel: 'End',
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