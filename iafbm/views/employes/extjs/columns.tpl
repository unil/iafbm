[{
    header: "Nom",
    //width: 170,
    dataIndex: 'personne_nom',
    editor: {
        xtype: 'textfield',
//        allowBlank: false
    }
},{
    header: "Prénom",
    //width: 170,
    dataIndex: 'personne_prenom',
    editor: {
        xtype: 'textfield',
        allowBlank: false
    }
},{
    header: "Adresse",
    //width: 170,
    dataIndex: 'personne_adresse',
    editor: {
        xtype: 'textfield',
        allowBlank: false
    }
},{
    header: "Téléphone",
    //width: 170,
    dataIndex: 'personne_tel',
    editor: {
        xtype: 'textfield',
        allowBlank: false
    }
},{
    header: "Pays",
    //width: 170,
    dataIndex: 'personne_pays_id',
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
    header: "Date de naissance",
    //width: 170,
    dataIndex: 'personne_date_naissance',
    renderer: function(value) { return value ? value.dateFormat('d-M-Y') : '' },
    editor: {
        xtype: 'datefield',
        startDay: 1,
        //allowBlank: false,
        format: 'Y-d-m',
        altFormat: 'Y-m-d H:i:s'
    }
},{
    header: "Date de retraite",
    //width: 170,
    dataIndex: 'date_retraite',
    renderer: function(value) { return value ? value.dateFormat('d-M-Y') : '' },
    editor: {
        xtype: 'datefield',
        startDay: 1,
        //allowBlank: false,
        format: 'Y-d-m',
        altFormat: 'Y-m-d H:i:s'
    }
},{
    header: "Section",
    //width: 170,
    dataIndex: 'section',
    editor: {
        xtype: 'textfield'
    }
}]