[{
    header: "Nom",
    dataIndex: 'nom',
    editor: {
        xtype: 'textfield',
        allowBlank: false
    }
},{
    xtype: 'booleancolumn',
    trueText: 'Oui',
    falseText: 'Non',
    header: 'Actif',
    dataIndex: 'actif',
    align: 'center',
    editor: {
        xtype: 'checkbox'
    }
}]