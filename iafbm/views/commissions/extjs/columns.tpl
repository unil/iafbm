[{
    header: "Nom",
    dataIndex: 'nom',
    flex: 1,
    editor: {
        xtype: 'textfield',
        allowBlank: false
    }
},{
    header: "Description",
    dataIndex: 'description',
    flex: 1,
    editor: {
        xtype: 'textfield',
        allowBlank: false
    }
},{
    header: "Type",
    dataIndex: 'commission-type_id',
    flex: 1,
    editor: {
        xtype: 'ia-combo',
        lazyRender: true,
        typeAhead: true,
        minChars: 1,
        triggerAction: 'all',
        displayField: 'nom',
        valueField: 'id',
        //allowBlank: false,
        store: new iafbm.store.CommissionType({})
    }
},{
    header: "Actif",
    dataIndex: 'actif',
    xtype: 'booleancolumn',
    trueText: 'Oui',
    falseText: 'Non',
    width: 25,
    flex: 1,
    editor: {
        xtype: 'checkbox'
    }
}]
