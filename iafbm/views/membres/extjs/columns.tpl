[{
    header: "Nom",
    dataIndex: 'personne_nom',
    editor: {
        xtype: 'textfield',
        allowBlank: false
    }
},{
    header: "Prénom",
    dataIndex: 'personne_prenom',
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