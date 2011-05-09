[{
    header: "Nom",
    dataIndex: 'nom',
    flex: 1,
    editor: {
        xtype: 'textfield',
        allowBlank: false
    }
},{
    header: "Prénom",
    dataIndex: 'prenom',
    flex: 1,
    editor: {
        xtype: 'textfield',
        allowBlank: false
    }
},{
    header: "Adresse",
    dataIndex: 'adresse',
    flex: 1,
    editor: {
        xtype: 'textfield',
        allowBlank: false
    }
},{
    header: "Téléphone",
    dataIndex: 'tel',
    flex: 1,
    editor: {
        xtype: 'textfield',
        allowBlank: false
    }
}, {
    header: "Pays",
    dataIndex: 'pays_id',
    flex: 1,
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
            storeId: 'store-pays',
            model: 'Pays',
            proxy: {
                type: 'rest',
                url : '<?php echo u('api/pays') ?>',
                reader: {
                    type: 'json',
                    root: 'items'
                }
            },
            listeners: {
                load: function() {
//console.log(a=this);
                }
            },
            autoLoad: true
        })
    },
    renderer: function(value, metaData, record, rowIndex, colIndex, store) {
        var store = Ext.data.StoreManager.lookup('store-pays');
        return store.getById(value) ? store.getById(value).get('nom') : '...';
    }
}, {
    header: "Date de naissance",
    dataIndex: 'date_naissance',
    flex: 1,
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
    flex: 1,
    editor: {
        xtype: 'checkbox'
    }
}]
