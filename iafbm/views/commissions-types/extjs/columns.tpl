[{
    header: "Nom",
    dataIndex: 'nom',
    editor: {
        xtype: 'textfield',
        allowBlank: false
    }
},{
    xtype: 'checkcolumn',
    //xtype: 'booleancolumn',
    //trueText: 'Oui',
    //falseText: 'Non',
    header: 'Actif',
    dataIndex: 'actif',
    align: 'center',
    width: 25,
    editor: {
        xtype: 'checkbox'
    }
}]