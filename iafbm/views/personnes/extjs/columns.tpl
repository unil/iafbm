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
        //lazyRender: true,
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
            idProperty: 'id',
            fields: ['id', 'nom'],
            //autoLoad: true
        })
    }
},{
    header: 'Pays test',
    dataIndex: 'pays_id',
    editor: new Ext.form.ComboBox({
        typeAhead: true,
        triggerAction: 'all',
        lazyRender: true,
        store: new Ext.data.JsonStore({
            autoDestroy: true,
            url: '/api/pays',
            restful: true,
            idProperty: 'id',
            fields: ['id', 'nom'],
            //autoLoad: true
        })
    })
},{
    header: "Date de naissance",
    dataIndex: 'date_naissance',
    renderer: function(value) { return value ? value.dateFormat('d-M-Y') : '' },
    editor: {
        // TODO: date submit format is not good :(
        xtype: 'xdatefield',
        startDay: 1,
        //allowBlank: false,
        format: 'Y-d-m',
        submitFormat: 'timestamp',
    }
},{
    header: "Actif",
    dataIndex: 'actif',
    xtype: 'checkcolumn',
    width: 25
}]