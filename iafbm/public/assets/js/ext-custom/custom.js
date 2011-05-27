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
    timeout: 10000,
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
    },
    listeners: {
        exception: function(proxy, response, operation) {
            Ext.Msg.show({
                title: 'Erreur',
                msg: "Une erreur est survenue pendant la lecture ou l'écriture des données",
                buttons: Ext.Msg.OK,
                icon: Ext.window.MessageBox.QUESTION
            });
        }
    }
});

Ext.define('Ext.ia.form.field.Date', {
    extend:'Ext.form.field.Date',
    alias: 'widget.ia-datefield',
    format: 'd.m.Y',
    altFormats: 'd.m.Y|d-m-Y|d m Y',
    startDay: 1,
    // TODO: see Ext.field.Combobox.rawToValue() !!!!!!!
    valueToRaw: function(value) {
        return this.formatDate(this.parseDate(value));
    },
    parseDate : function(value) {
        if(!value || Ext.isDate(value)){
//console.log('parseDate(1)', {
//    value: value,
//    return: value
//});
            return value;
        }

        var me = this,
            val = me.safeParse(value, me.format),
            altFormats = me.altFormats,
            altFormatsArray = me.altFormatsArray,
            i = 0,
            len;

        if (!val && altFormats) {
            altFormatsArray = altFormatsArray || altFormats.split('|');
            len = altFormatsArray.length;
            for (; i < len && !val; ++i) {
                val = me.safeParse(value, altFormatsArray[i]);
            }
        }
//console.log('parseDate(2)', {
//    value: value,
//    return: val
//});
        return val;
    },
    formatDate : function(date){
        var val = Ext.isDate(date) ? Ext.Date.dateFormat(date, this.format) : date;
//console.log('formatDate', {
//    value: date,
//    return: val,
//    format: this.format
//});
        return val;
    }

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
        var me = this; me.callParent();
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
        pageSize: 10
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
                var grid = this.up('gridpanel');
                var selection = grid.getView().getSelectionModel().getSelection()[0];
                if (selection) {
                    grid.store.remove(selection);
                }
            }
        }, '->', '-', 'Rechercher',
        new Ext.ux.form.SearchField({
            store: null,
            emptyText: 'Mots-clés',
            listeners: {
                // Wait for render time so that the grid store is created
                // and ready to be bound to the search field
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
        var me = this;
        me.callParent();
        this.store.pageSize = this.pageSize;
        this.store.autoSync = true;
        this.store.load();
    }
});

Ext.define('Ext.ia.form.Panel', {
    extend: 'Ext.form.Panel',
    alias: 'widget.ia-form',
    autoHeight: true,
    bodyPadding: 10,
    border: 0,
    defaults: {
        //anchor: '100%',
        msgTarget: 'side'
    },
    fieldDefaults: {
        labelWidth: 80
    },
    store: null,
    loadParams: {},
    initComponent: function() {
        if (this.store) this.buttons = [{
            text: 'Save',
            handler: function() {
                var form = this.up('form').getForm();
                var store = this.up('form').store;
                if (form.isValid()) {
                    var values = Ext.apply(form.getValues());
                    store.getAt(0).set(values);
//console.log(values.date, store.getAt(0).get('date'));
                    store.getAt(0).save();
                }
            }
        }];
        var me = this; me.callParent();
    },
    listeners: {
        beforerender: function() {
            // Store loading is optional
            if (!this.store) return;
            // Store autoloading logic
            var me = this;
            if (this.store.getCount() == 0) {
                this.store.load({ params: this.loadParams,
                    callback: function(records, operation, success) {
                        var record = me.store.getAt(0);
                        if (record) {
                            me.form.loadRecord(record);
                        } else {
                            Ext.Error.raise('Failed loading store');
                        }
                    }
                });
            } else {
                this.form.loadRecord(this.store.getAt(0));
            }
        }
    }
});


Ext.define('Ext.ia.ux.grid.History', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.ia-history',
    title: 'Historique',
    columns: [{
        header: 'Champs',
        dataIndex: 'field',
        width: 100,
        field: {
            xtype: 'textfield'
        }
    }, {
        header: 'Valeur',
        dataIndex: 'value',
        flex: 1,
        field: {
            xtype: 'textfield'
        }
    }, {
        header: 'Date',
        dataIndex: 'date',
        width: 100,
        field: {
            xtype: 'textfield'
            //xtype: 'ia-datefield'
        }
    }, {
        header: 'Utilisateur',
        dataIndex: 'user',
        width: 100,
        field: {
            xtype: 'textfield'
        }
    }],
    // Dummy store with dummy data
    store: new Ext.data.ArrayStore({
        autoDestroy: true,
        fields: [
            {name: 'field'},
            {name: 'value'},
            {name: 'date'},//, type: 'date', dateFormat: 'd.m.Y'},
            {name: 'user'}
        ],
        data: [
            ['Descrption', 'Promenade cowpoke dumb rustle plumb, highway, redblooded, ails tobaccee, has, tonic buy.', '03.05.2011', 'smeier06@unil.ch'],
            ['Commentaire', 'Plug-nickel caboodle hoosegow caught hobo grandpa aunt.', '01.06.2011', 'dcorpata@unil.ch'],
        ]
    })
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
        {name: 'actif', type: 'bool', defaultValue: true}
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
        {name: 'titre', type: 'string', defaultValue: 'Prof.'},
        {name: 'actif', type: 'bool', defaultValue: true}
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
        {name: 'personne_display_nom', mapping: 0, convert: function(value, record) {
            return [
                record.get('personne_prenom'),
                record.get('personne_nom'),
                '[H]'].join(' ');
        }},
        {name: 'actif', type: 'bool', defaultValue: true}
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
Ext.define('iafbm.model.Section', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'code', type: 'string'},
        {name: 'nom', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/sections',
    }
});
Ext.define('iafbm.model.Commission', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'},
        {name: 'description', type: 'string'},
        {name: 'commission-type_id', type: 'int'},
        {name: 'commission-etat_id', type: 'int'},
        {name: 'section_id', type: 'int'},
        {name: 'actif', type: 'bool', defaultValue: true}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions',
    }
});
Ext.define('iafbm.model.CommissionEtat', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'},
        {name: 'description', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions-etats',
    }
});
Ext.define('iafbm.model.CommissionType', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'},
        {name: 'actif', type: 'bool', defaultValue: true}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions-types',
    }
});
Ext.define('iafbm.model.CommissionFonction', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'},
        {name: 'description', type: 'string'},
        {name: 'actif', type: 'bool', defaultValue: true}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions-fonctions',
    }
});
Ext.define('iafbm.model.CommissionCreation', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'commission_id', type: 'int'},
        {name: 'actif', type: 'bool', defaultValue: true},
        {name: 'decision', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'ordrejour', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'authorisation', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'annonce', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'composition', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'composition_validation', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'commentaire', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions-creations',
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
Ext.define('iafbm.store.Section', {
    extend: 'Ext.ia.data.Store',
    model: 'iafbm.model.Section'
});
Ext.define('iafbm.store.Commission', {
    extend: 'Ext.ia.data.Store',
    model: 'iafbm.model.Commission'
});
Ext.define('iafbm.store.CommissionEtat', {
    extend: 'Ext.ia.data.Store',
    model: 'iafbm.model.CommissionEtat'
});
Ext.define('iafbm.store.CommissionType', {
    extend: 'Ext.ia.data.Store',
    model: 'iafbm.model.CommissionType'
});
Ext.define('iafbm.store.CommissionFonction', {
    extend: 'Ext.ia.data.Store',
    model: 'iafbm.model.CommissionFonction'
});
Ext.define('iafbm.store.CommissionCreation', {
    extend: 'Ext.ia.data.Store',
    model: 'iafbm.model.CommissionCreation'
});

// columns
Ext.ns('iafbm.columns');
var pays_store = Ext.create('Ext.data.Store', {
    fields: ['nom', 'id'],
    data: [
        {nom: 'Oui', id: 2},
        {nom: 'Non', id: 1},
    ]
});
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
}];

iafbm.columns.Membre = [{
    header: "Titre",
    dataIndex: '',
    flex: 1,
    field: {
        xtype: 'textfield'
    }
}, {
    header: "Nom",
    dataIndex: 'personne_nom',
    flex: 1,
    field: {
        xtype: 'textfield'
    }
}, {
    header: "Prénom",
    dataIndex: 'personne_prenom',
    flex: 1,
    field: {
        xtype: 'textfield',
        editable: false
    }
}, {
    header: "Fonction",
    dataIndex: 'fonction_id',
    flex: 1,
    editor: {
        xtype: 'ia-combo',
        lazyRender: true,
        typeAhead: true,
        minChars: 1,
        triggerAction: 'all',
        displayField: 'nom',
        valueField: 'id',
        allowBlank: false,
        store: new iafbm.store.CommissionFonction({autoLoad:true})
    }
}];

iafbm.columns.Candidat = [{
    header: "Titre",
    dataIndex: '',
    flex: 1,
    field: {
        xtype: 'textfield'
    }
}, {
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
}, {
    header: "Date de naissance",
    dataIndex: 'date_naissance',
    flex: 1,
    field: {
        xtype: 'ia-datefield'
    }
}, {
    header: "Sexe",
    dataIndex: '',
    flex: 1,
    field: {
        xtype: 'textfield'
    }
}, {
    header: "Formation supérieure",
    dataIndex: '',
    flex: 1,
    field: {
        xtype: 'textfield'
    }
}, {
    header: "Position actuelle",
    dataIndex: '',
    flex: 1,
    field: {
        xtype: 'textfield'
    }
}, {
    header: "Détails",
    xtype: 'actioncolumn',
    width: 25,
    items: [{
        icon: x.context.baseuri+'/a/img/ext/page_white_magnify.png',  // Use a URL in the icon config
        text: 'Détails',
        tooltip: 'Détails',
        handler: function(grid, rowIndex, colIndex, item) {
            var personne_id = grid.store.getAt(0).get('personne_id');
            var l = window.location;
            var url = [l.protocol, '//', l.host, '/personnes/', personne_id].join('');
            window.location = url;
        }
    }]
}];

iafbm.columns.Commission = [{
    header: "Type",
    dataIndex: 'commission-type_id',
    width: 175,
    field: {
        xtype: 'ia-combo',
        lazyRender: true,
        typeAhead: true,
        minChars: 1,
        triggerAction: 'all',
        displayField: 'nom',
        valueField: 'id',
        allowBlank: false,
        store: new iafbm.store.CommissionType({})
    }
}, {
    header: "N°",
    dataIndex: 'id',
    width: 75,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
}, {
    header: "Nom",
    dataIndex: 'nom',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
}, {
    header: "Section",
    dataIndex: 'section_id',
    width: 75,
    field: {
        xtype: 'ia-combo',
        lazyRender: true,
        typeAhead: true,
        minChars: 1,
        triggerAction: 'all',
        displayField: 'code',
        valueField: 'id',
        allowBlank: false,
        store: new iafbm.store.Section({})
    }
}, {
    header: "Président",
    dataIndex: '',
    width: 150,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
}, {
    header: "Etat",
    dataIndex: 'commission-etat_id',
    width: 100,
    field: {
        xtype: 'ia-combo',
        lazyRender: true,
        typeAhead: true,
        minChars: 1,
        triggerAction: 'all',
        displayField: 'nom',
        valueField: 'id',
        allowBlank: false,
        store: new iafbm.store.CommissionEtat({})
    }
}, {
    xtype: 'actioncolumn',
    width: 25,
    items: [{
        icon: x.context.baseuri+'/a/img/ext/page_white_magnify.png',  // Use a URL in the icon config
        text: 'Détails',
        tooltip: 'Détails',
        handler: function(grid, rowIndex, colIndex, item) {
            var id = this.up('gridpanel').store.getAt(rowIndex).get('id');
            location.href = x.context.baseuri+'/commissions/'+id;
        }
    }]
}];

iafbm.columns.CommissionType = [{
    header: "Nom",
    dataIndex: 'nom',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
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

// TODO



/******************************************************************************
 * Temporary local stores
 * FIXME: waiting for "remote combo within grid bug" workaround
 */
Ext.ns('iafbm.localdata');
iafbm.localdata.CommissionType;
iafbm.localdata.CommissionEtat;
iafbm.localdata.Pays=[];
//new iafbm.store.Pays().load(function() { this.data.each(function(r) { iafbm.localdata.Pays.push(r.data) }) });