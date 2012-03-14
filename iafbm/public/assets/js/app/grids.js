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
        editor: {
            xtype: 'ia-combo',
            store: new Ext.data.ArrayStore({
                fields: ['value'],
                data: Ext.Array.createArrayStoreRange(0, 31)
            }),
            valueField: 'value',
            displayField: 'value'
        }
    },{
        header: 'Mois',
        dataIndex: '_date_these_mois',
        width: 40,
        editor: {
            xtype: 'ia-combo',
            store: new Ext.data.ArrayStore({
                fields: ['value'],
                data: Ext.Array.createArrayStoreRange(0, 12)
            }),
            valueField: 'value',
            displayField: 'value'
        }
    },{
        header: 'Année',
        dataIndex: '_date_these_annee',
        width: 55,
        editor: {
            xtype: 'ia-combo',
            store: new Ext.data.ArrayStore({
                fields: ['value'],
                data: Ext.Array.createArrayStoreRange(0, 2050)
            }),
            valueField: 'value',
            displayField: 'value'
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
    columns: [{
        header: "Type",
        dataIndex: 'adresse_adresse_type_id',
        width: 85,
        xtype: 'ia-combocolumn',
        editor: {
            xtype: 'ia-combo',
            store: new iafbm.store.AdresseType(),
            valueField: 'id',
            displayField: 'nom',
            allowBlank: false
        }
    },{
        header: "Adresse",
        dataIndex: 'adresse_rue',
        flex: 1,
        renderer: function(value) {
            // Converts NL|CR into <br/> for field display
            var breakTag = '<br/>';
            value = (value + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
            return ['<div style="white-space:normal">', value, '</div>'].join('');
        },
        editor: {
            //xtype: 'textfield',
            xtype: 'ia-textarea',
            grow: true,
            growMin: 22,
            growMax: 22,
            fixEditorHeight: function() {
                // FIXME: Not used, should be deleted after feature validation
                // Sets RowEditor panel height according textarea height
                var editorHeight = this.el.down('textarea').getHeight();
                this.up('panel').setHeight(editorHeight+20);
            },
            fireKey: function(event) {
                // Accepts ENTER as regular key
                if (event.getKey() == event.ENTER) event.stopPropagation();
                //this.fixEditorHeight();
            },
            //listeners: {focus: function() {
            //    this.fixEditorHeight();
            //}}
        }
    },{
        header: "NPA",
        dataIndex: 'adresse_npa',
        width: 40,
        editor: {
            xtype: 'textfield',
            maskRe: /[0-9]/
        }
    },{
        header: "Lieu",
        dataIndex: 'adresse_lieu',
        width: 100,
        editor: {
            xtype: 'textfield',
        }
    },{
        header: "Pays",
        dataIndex: 'adresse_pays_id',
        width: 100,
        xtype: 'ia-combocolumn',
        editor: {
            xtype: 'ia-combo',
            store: new iafbm.store.Pays(),
            valueField: 'id',
            displayField: 'nom',
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