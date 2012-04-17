// Grids
Ext.ns('iafbm.grid.common');

Ext.define('iafbm.grid.common.Formations', {
    extend: 'Ext.ia.grid.EditPanel',
    height: 120,
    toolbarButtons: ['add', 'delete'],
    bbar: null,
    newRecordValues: {},
    store: null,
    listeners: {},
    columns: [{
        header: "Formation",
        dataIndex: 'formation_id',
        width: 100,
        xtype: 'ia-combocolumn',
        editor: {
            xtype: 'ia-combo',
            store: new iafbm.store.Formation(),
            valueField: 'id',
            displayField: 'abreviation',
            allowBlank: false
        }
    },{
        header: "Lieu (Ville / Pays)",
        dataIndex: 'lieu_these',
        flex: 1,
        editor: {
            xtype: 'textfield'
        }
    },{
        header: 'Jour',
        dataIndex: '_date_these_jour',
        width: 40,
        xtype: 'ia-combocolumn',
        editor: {
            xtype: 'ia-combo',
            mode: 'local',
            store: new Ext.data.ArrayStore({
                fields: ['value', 'label'],
                data: Ext.ia.staticdata.Days
            }),
            valueField: 'value',
            displayField: 'label'
        }
    },{
        header: 'Mois',
        dataIndex: '_date_these_mois',
        width: 55,
        xtype: 'ia-combocolumn',
        editor: {
            xtype: 'ia-combo',
            store: new Ext.data.ArrayStore({
                fields: ['value', 'label'],
                data: Ext.ia.staticdata.Months
            }),
            valueField: 'value',
            displayField: 'label'
        }
    },{
        header: 'Année',
        dataIndex: '_date_these_annee',
        width: 55,
        xtype: 'ia-combocolumn',
        editor: {
            xtype: 'ia-combo',
            store: new Ext.data.ArrayStore({
                fields: ['value', 'label'],
                data: Ext.ia.staticdata.Years
            }),
            valueField: 'value',
            displayField: 'label'
        }
    },{
        header: "Commentaire",
        dataIndex: 'commentaire',
        flex: 1,
        editor: {
            xtype: 'textfield'
        }
    }]
});

Ext.define('iafbm.grid.common.Adresses', {
    extend: 'Ext.ia.grid.EditPanel',
    height: 120,
    toolbarButtons: ['add', 'delete'],
    bbar: null,
    newRecordValues: {},
    store: null,
    columns: iafbm.columns.PersonneAdresse
});

Ext.define('iafbm.grid.common.Telephones', {
    extend: 'Ext.ia.grid.EditPanel',
    height: 120,
    toolbarButtons: ['add', 'delete'],
    bbar: null,
    newRecordValues: {},
    store: null,
    columns: [{
        header: "Type",
        dataIndex: 'adresse_type_id',
        width: 100,
        xtype: 'ia-combocolumn',
        editor: {
            xtype: 'ia-combo',
            store: new iafbm.store.AdresseType(),
            valueField: 'id',
            displayField: 'nom',
            allowBlank: false
        }
    },{
        header: "Indicatif pays",
        dataIndex: 'countrycode',
        xtype: 'templatecolumn',
        tpl: '<tpl if="countrycode.length &gt; 0">+</tpl>{countrycode}',
        width: 38,
        editor: {
            xtype: 'textfield',
            maxLength: 3,
            enforceMaxLength: true,
            maskRe: /[0-9]/,
            vtype: 'telcc',
            allowBlank: false
        }
    },{
        header: "Téléphone",
        dataIndex: 'telephone',
        flex: 1,
        editor: {
            xtype: 'textfield',
            maskRe: /[0-9]/,
            allowBlank: false
        }
    }/*,{
        header: "Par défaut",
        dataIndex: 'defaut',
        width: 65,
        xtype: 'ia-radiocolumn',
        editor: {
            xtype: 'checkboxfield',
            disabled: true
        }
    }*/]
});

Ext.define('iafbm.grid.common.Emails', {
    extend: 'Ext.ia.grid.EditPanel',
    height: 120,
    toolbarButtons: ['add', 'delete'],
    bbar: null,
    newRecordValues: {},
    store: null,
    iaDisableFor: [],
    columns: [{
        header: "Type",
        dataIndex: 'adresse_type_id',
        width: 100,
        xtype: 'ia-combocolumn',
        editor: {
            xtype: 'ia-combo',
            store: new iafbm.store.AdresseType(),
            valueField: 'id',
            displayField: 'nom',
            allowBlank: false
        }
    },{
        header: "Email",
        dataIndex: 'email',
        flex: 1,
        editor: {
            xtype: 'textfield',
            allowBlank: false,
            vtype: 'email'
        }
    }/*,{
        header: "Par défaut",
        dataIndex: 'defaut',
        width: 65,
        xtype: 'ia-radiocolumn',
        editor: {
            xtype: 'checkbox',
            disabled: true
        }
    }*/]
});