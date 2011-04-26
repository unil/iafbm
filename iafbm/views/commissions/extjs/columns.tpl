[{
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
}]