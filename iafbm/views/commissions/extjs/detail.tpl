<div id="form"></div>

<script type="text/javascript">

Ext.onReady(function() {

    Ext.QuickTips.init();

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

    var rowEditing = new Ext.grid.plugin.RowEditing();

//    var grid = Ext.create('Ext.grid.Panel', {
    var grid = new Ext.grid.Panel({
        id: 'commissions_grid',
        title: 'Commissions',
        iconCls: 'icon-user',
        //renderTo: 'editor-grid',
        loadMask: true,
        width: 880,
        height: 300,
        frame: true,
        plugins: [rowEditing],
        store: store,
        columns: [{
            header: "Nom",
            dataIndex: 'nom',
            editor: {
                xtype: 'textfield',
                allowBlank: false
            }
        },{
            header: "Description",
            dataIndex: 'description',
            editor: {
                xtype: 'textfield',
                allowBlank: false
            }
        },{
            header: "Type",
            dataIndex: 'commission-type_id',
            editor: {
                xtype: 'combo',
                lazyRender: true,
                typeAhead: true,
                minChars: 1,
                triggerAction: 'all',
                displayField: 'nom',
                valueField: 'id',
                //allowBlank: false,
                store: new Ext.data.Store({
                    model: 'CommissionType',
                    proxy: {
                        type: 'rest',
                        url : '/api/commissions-types',
                        reader: {
                            type: 'json',
                            root: 'items'
                        }
                    },
                    autoLoad: true
                })
            },
            _renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                var store = this.getEditor().store;
                return store.getById(value) ? store.getById(value).get('nom') : '...';
            }
        },{
            header: "Actif",
            dataIndex: 'actif',
            xtype: 'booleancolumn',
            trueText: 'Oui',
            falseText: 'Non',
            width: 25,
            editor: {
                xtype: 'checkbox'
            }
        }],
        dockedItems: [{
            xtype: 'toolbar',
            items: [{
                text: 'Add',
                iconCls: 'icon-add',
                handler: function(){
                    // empty record
                    store.insert(0, new Commission());
                    rowEditing.startEdit(0, 0);
                }
            }, '-', {
                text: 'Delete',
                iconCls: 'icon-delete',
                handler: function(){
                    var selection = grid.getView().getSelectionModel().getSelection()[0];
                    if (selection) {
                        store.remove(selection);
                    }
                }
            }, '-', 'Rechercher:', {
                xtype:'textfield',
                enableKeyEvents: true,
                id: 'query',
                listeners: {
                    'keyup': function(c, e) {
                        //if (e.getKey() !== e.ENTER) return;
                        var store = Ext.getCmp('commissions_grid').store;
                        if (this.getValue().length > 0) store.proxy.extraParams.query = this.getValue();
                        else delete store.proxy.extraParams.query;
                        store.load();
                    }
                }
            }]
        }],
        bbar: new Ext.PagingToolbar({
            store: store,
            displayInfo: true,
            displayMsg: 'Affichage des éléments {0} à {1} sur {2}',
            emptyMsg: "Pas d'éléments à afficher",
            items:[]
        })
    });


    <?php echo xView::load('commissions/extjs4/model')->render() ?>

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
        }, {
            xtype: 'fieldset',
            title: 'Composition de la commission',
            collapsible: true,
            defaults: defaults_fieldset,
            items: [grid]
        },{
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