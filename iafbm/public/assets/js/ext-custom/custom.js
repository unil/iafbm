/******************************************************************************
 * Checks
 */
//<debug>

if (typeof(x)=='undefined'||typeof(x.context)=='undefined'||typeof(x.context.baseuri)=='undefined')
    Ext.Error.raise("x.context.baseuri must be defined");
//</debug>



/******************************************************************************
 * Date i18n
**/
Ext.Date.dayNames = [
    "Dimanche",
    "Lundi",
    "Mardi",
    "Mercredi",
    "Jeudi",
    "Vendredi",
    "Samedi"
];
Ext.Date.monthNames = [
    "janvier",
    "février",
    "mars",
    "avril",
    "mai",
    "juin",
    "juillet",
    "août",
    "septembre",
    "octobre",
    "novembre",
    "décembre"
];
Ext.Date.monthNumbers = {
    'jan':0,
    'fév':1,
    'mar':2,
    'avr':3,
    'mai':4,
    'jui':5,
    'juil':6,
    'aou':7,
    'sep':8,
    'oct':9,
    'nov':10,
    'dec':11
};
// This is a custom array for overriden Date.getShortMonthName()
Ext.Date.shortMonthNames = [
    'jan',
    'fév',
    'mar',
    'avr',
    'mai',
    'jui',
    'juil',
    'aou',
    'sep',
    'oct',
    'nov',
    'dec'
];
Ext.Date.getShortMonthName = function(month) {
    return Ext.Date.shortMonthNames[month];
};
Ext.Date.defaultFormat = 'd m Y';



/******************************************************************************
 * Ext classes customization
**/

Ext.define('Ext.ia.data.Store', {
    extend:'Ext.data.Store',
    alias: 'store.ia-store',
    pageSize: null,
    autoLoad: false,
    autoSync: false
});

Ext.define('Ext.ia.data.proxy.Rest', {
    extend:'Ext.data.proxy.Rest',
    alias: 'proxy.ia-rest',
    type: 'rest',
    limitParam: 'xlimit',
    startParam: 'xoffset',
    pageParam: undefined,
    reader: {
        type: 'json',
        root: 'items',
        totalProperty: 'xcount'
    },
    writer: {
        root: 'items'
    },
    actionMethods: {
        read: 'get',
        create: 'put',
        update: 'post',
        destroy: 'delete'
    }
});

Ext.define('Ext.ia.form.field.Date', {
    extend:'Ext.form.field.Date',
    alias: 'widget.ia-datefield',
    format: 'd.m.Y',
    altFormats: 'd.m.Y|d-m-Y|d m Y',
    startDay: 1
});

Ext.define('Ext.ia.form.field.ComboBox', {
    extend:'Ext.form.field.ComboBox',
    alias: 'widget.ia-combo',
    // Workaround for displayField issue (not yet working)
    renderer: function(value, metaData, record, rowIndex, colIndex, store) {
        var store = Ext.data.StoreManager.lookup('editor-grid-store');
        return store.getById(value) ? store.getById(value).get('nom') : '...';
    }
});

Ext.define('Ext.ia.selectiongrid.Panel', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.ia-selectiongrid',
    //uses:?
    requires: [
        'Ext.grid.Panel',
        'Ext.form.field.ComboBox'
    ],
    config: {
        combo: {
            store: null,
            tpl: null, //TODO
        },
        grid: {
             store: null
        },
        makeData: function(record) {
            // Returns a hashtable for feeding Ext.data.Model data, eg:
            // return {
            //     field1: record.get('id'),
            //     field2: record.get('name'),
            //     field3: 'static value'
            // }
            return record.data;
        }
    },
    initComponent: function() {
        this.grid.store.load();
        // Component
        this.store = this.grid.store;
        this.columns = this.grid.columns;
        this.tbar = [
            'Ajouter',
            this.getCombo()
        ];
        this.bbar = [{
            text: 'Supprimer la sélection',
            iconCls: 'icon-delete',
            handler: function() {
                var grid = this.up('gridpanel');
                var selection = grid.getView().getSelectionModel().getSelection()[0];
                if (selection) grid.store.remove(selection);
                grid.store.sync();
            }
        }];
        var me = this;
        me.callParent();
        //Ext.ia.selectiongrid.Panel.superclass.initComponent.call(this, arguments);
    },
    getCombo: function() {
        //return new Ext.ia.form.field.ComboBox({
        return new Ext.form.field.ComboBox({
            store: this.combo.store,
            pageSize: 5,
            limitParam: undefined,
            startParam: undefined,
            pageParam: undefined,
            typeAhead: false,
            minChars: 1,
            hideTrigger: true,
            width: 350,
            listConfig: {
                loadingText: 'Recherche...',
                emptyText: 'Aucun résultat.',
                // Custom rendering template for each item
                getInnerTpl: function() {
                    var img = x.context.baseuri+'/a/img/icons/trombi_empty.png';
                    return [
                        '<div>',
                        '<img src="'+img+'" style="float:left;margin-right:5px"/>',
                        '<h3>{prenom} {nom}</h3>',
                        '<div>{adresse}, {pays_nom}</div>',
                        '<div>{pays_id}, {pays_nom}, {pays_nom_en}, {pays_code}</div>',
                        '<div>{[Ext.Date.format(values.date_naissance, "j M Y")]}</div>',
                        '</div>'
                    ].join('');
                }
            },
            listeners: {
                select: function(combo, selection) {
                    // Inserts record into grid store
                    var grid = this.up('gridpanel'),
                        records = [];
                    Ext.each(selection, function(record) {
                        records.push(new grid.store.model(grid.makeData(record)));
                    });
                    grid.store.insert(grid.store.getCount(), records);
                    grid.store.sync();
                    this.clearValue();
                },
                blur: function() { this.clearValue() }
            }
        });
    }
});

Ext.define('Ext.ia.grid.EditPanel', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.ia-editgrid',
    config: {
        loadMask: true,
        width: 880,
        height: 300,
        frame: true,
        store: null,
        columns: null,
    },
    plugins: [new Ext.grid.plugin.RowEditing({pluginId:'rowediting'})],
    dockedItems: [{
        xtype: 'toolbar',
        items: [{
            text: 'Ajouter',
            iconCls: 'icon-add',
            handler: function(){
                // empty record
                var grid = this.up('gridpanel');
                grid.store.autoSync = false;
                grid.store.insert(0, new grid.store.model());
                grid.store.autoSync = true;
                grid.getPlugin('rowediting').startEdit(0, 0);
            }
        }, '-', {
            text: 'Supprimer',
            iconCls: 'icon-delete',
            handler: function(){
                var selection = grid.getView().getSelectionModel().getSelection()[0];
                if (selection) {
                    this.up('gridpanel').store.remove(selection);
                }
            }
        }, '->', '-', 'Rechercher',
        new Ext.ux.form.SearchField({
            store: null,
            emptyText: 'Mots-clés',
            listeners: {
                beforerender: function() { this.store = this.up('gridpanel').store }
            }
        })]
    }],
    bbar: new Ext.PagingToolbar({
        store: null,
        displayInfo: true,
        displayMsg: 'Eléments {0} à {1} sur {2}',
        emptyMsg: "Aucun élément à afficher",
        items:[],
        listeners: {
            // Wait for render time so that the grid store is created
            // and ready to be bound to the pager
            beforerender: function() { this.bindStore(this.up('gridpanel').store) }
        }
        //plugins: Ext.create('Ext.ux.ProgressBarPager', {})
    }),
    initComponent: function() {
        this.store.pageSize = 10;
        this.store.autoSync = true;
        this.store.load();
        var me = this;
        me.callParent();
    }
});


/******************************************************************************
 * Business objects
**/

// Models
Ext.define('iafbm.model.Personne', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'},
        {name: 'prenom', type: 'string'},
        {name: 'adresse', type: 'string'},
        {name: 'pays_id', type: 'int'},
        {name: 'tel', type: 'string'},
        {name: 'date_naissance', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'actif', type: 'bool'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/personnes',
    }
});
Ext.define('iafbm.model.Membre', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'personne_id', type: 'int'},
        {name: 'fonction_id', type: 'int'},
        {name: 'commission_id', type: 'int'},
        {name: 'personne_nom', type: 'string'},
        {name: 'personne_prenom', type: 'string'},
        {name: 'actif', type: 'bool'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/membres',
    }
});
Ext.define('iafbm.model.Candidat', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'personne_id', type: 'int'},
        {name: 'commission_id', type: 'int'},
        {name: 'personne_nom', type: 'string'},
        {name: 'personne_prenom', type: 'string'},
        {name: 'actif', type: 'bool'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/candidats',
    }
});
Ext.define('iafbm.model.Pays', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'code', type: 'string'},
        {name: 'nom', type: 'string'},
        {name: 'nom_en', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/pays',
    }
});
Ext.define('iafbm.model.Commission', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'},
        {name: 'description', type: 'string'},
        {name: 'commission-type_id', type: 'commission_type_id'},
        {name: 'actif', type: 'bool'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions',
    }
});
Ext.define('iafbm.model.CommissionType', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'},
        {name: 'actif', type: 'bool'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions-types',
    }
});

// Store
Ext.define('iafbm.store.Personne', {
    extend: 'Ext.ia.data.Store',
    model: 'iafbm.model.Personne'
});
Ext.define('iafbm.store.Membre', {
    extend: 'Ext.ia.data.Store',
    model: 'iafbm.model.Membre'
});
Ext.define('iafbm.store.Candidat', {
    extend: 'Ext.ia.data.Store',
    model: 'iafbm.model.Candidat'
});
Ext.define('iafbm.store.Pays', {
    extend: 'Ext.ia.data.Store',
    model: 'iafbm.model.Pays'
});
Ext.define('iafbm.store.Commission', {
    extend: 'Ext.ia.data.Store',
    model: 'iafbm.model.Commission'
});
Ext.define('iafbm.store.CommissionType', {
    extend: 'Ext.ia.data.Store',
    model: 'iafbm.model.CommissionType'
});


// columns
Ext.ns('iafbm.columns');
iafbm.columns.Personne = [{
    header: "Nom",
    dataIndex: 'nom',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
},{
    header: "Prénom",
    dataIndex: 'prenom',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
},{
    header: "Adresse",
    dataIndex: 'adresse',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
},{
    header: "Téléphone",
    dataIndex: 'tel',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
}, {
    header: "Pays",
    dataIndex: 'pays_id',
    flex: 1,
    field: {
        xtype: 'ia-combo',
        lazyRender: true,
        typeAhead: true,
        minChars: 1,
        triggerAction: 'all',
        displayField: 'nom',
        valueField: 'id',
        //allowBlank: false,
        store: new iafbm.store.Pays({})
    }
}, {
    header: "Date de naissance",
    dataIndex: 'date_naissance',
    flex: 1,
    field: {
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
    field: {
        xtype: 'checkbox'
    }
}];

iafbm.columns.Membre = [{
    header: "Nom",
    dataIndex: 'personne_nom',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
}, {
    header: "Prénom",
    dataIndex: 'personne_prenom',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
}];

iafbm.columns.Candidat = [{
    header: "Nom",
    dataIndex: 'personne_nom',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
}, {
    header: "Prénom",
    dataIndex: 'personne_prenom',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
}];

iafbm.columns.Commission = [{
    header: "Nom",
    dataIndex: 'nom',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
},{
    header: "Description",
    dataIndex: 'description',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
},{
    header: "Type",
    dataIndex: 'commission-type_id',
    flex: 1,
    field: {
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
    field: {
        xtype: 'checkbox'
    }
}];

iafbm.columns.CommissionType = [{
    header: "Nom",
    dataIndex: 'nom',
    field: {
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
    field: {
        xtype: 'checkbox'
    }
}];

//iafbm.Personne.fields: not used, defined in Model


/******************************************************************************
 * Menu tree
 */

// TODO



/******************************************************************************
 * Application
 */
