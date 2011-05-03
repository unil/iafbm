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
}, {
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
        store: new Ext.data.Store({
            model: 'Pays',
            proxy: {
                type: 'rest',
                url : '/api/pays',
                reader: {
                    type: 'json',
                    root: 'items'
                }
            },
            autoLoad: false//true
        })
    },
    _renderer: function(value, metaData, record, rowIndex, colIndex, store) {
        var store = this.getEditor().store;
        return store.getById(value) ? store.getById(value).get('nom') : '...';
    }
}, {
    header: "Date de naissance",
    dataIndex: 'date_naissance',
    renderer: Ext.util.Format.dateRenderer('d F Y'),
    editor: {
        xtype: 'ia-datefield'
    }
},{
    xtype: 'booleancolumn',
    trueText: 'Oui',
    falseText: 'Non',
    header: 'Actif',
    dataIndex: 'actif',
    align: 'center',
    width: 25,
    editor: {
        xtype: 'checkbox'
    }
}]