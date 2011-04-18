[{
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
},{
    header: "Adresse",
    dataIndex: 'adresse',
    editor: {
        xtype: 'textfield',
        allowBlank: false
    }
},{
    header: "Téléphone",
    dataIndex: 'tel',
    editor: {
        xtype: 'textfield',
        allowBlank: false
    }
},{
    header: "Pays",
    dataIndex: 'pays_id',
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
            url: '/api/pays',
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
    header: "Date de naissance",
    dataIndex: 'date_naissance',
    renderer: Ext.util.Format.dateRenderer('d F Y'),
    editor: {
        xtype: 'datefield',
        startDay: 1,
        //allowBlank: false,
        format: 'Y-d-m',
    }
},{
    header: "Actif",
    dataIndex: 'actif',
    xtype: 'checkcolumn',
    width: 25
}]