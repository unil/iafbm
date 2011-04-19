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
        store: new Ext.data.JsonStore({
            autoDestroy: true,
            url: '/api/commissions-types',
            restful: true,
            root: 'items',
            idProperty: 'id',
            fields: ['id', 'nom'],
            autoLoad: true
        })
    },
    renderer: function(value, metaData, record, rowIndex, colIndex, store) {
        var store = this.getEditor().store;
        return store.getById(value) ? store.getById(value).get('nom') : '...';
    }
},{
    header: "Actif",
    dataIndex: 'actif',
    xtype: 'checkcolumn',
    width: 25
}]