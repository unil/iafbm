/******************************************************************************
 * Checks
 */
//<debug>

if (typeof(x)=='undefined'||typeof(x.context)=='undefined'||typeof(x.context.baseuri)=='undefined')
    Ext.Error.raise("x.context.baseuri must be defined");
//</debug>


/******************************************************************************
 * Additional locales
**/
if (Ext.grid.RowEditor) {
    Ext.apply(Ext.grid.RowEditor.prototype, {
        saveBtnText: 'Enregistrer',
        cancelBtnText: 'Annuler',
        errorsText: 'Erreurs',
        dirtyText: 'Vous devez enregistrer ou annuler vos modifications'
    });
}


/******************************************************************************
 * Ext classes customization
**/

/**
 * Extends Ext.data.Store with project default config options
 */
Ext.define('Ext.ia.data.Store', {
    extend:'Ext.data.Store',
    alias: 'store.ia-store',
    pageSize: null,
    autoLoad: false,
    autoSync: false,
    params: {},
    loaded: false,
    listeners: {
        beforeload: function() { this.proxy.extraParams = Ext.apply(this.proxy.extraParams, this.params) },
        load: function() { this.loaded = true }
    },
    // Params to be passed to the proxy
});

/**
 * Extends Ext.data.proxy.Rest with project default config options
 */
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
            var actions = {
                create: "l\'ecriture",
                read: "la lecture",
                update: "l'écriture",
                delete: "l'écriture"
            };
            var msg = ['Une erreur est survenue pendant', actions[operation.action], 'des données'].join(' ');
            Ext.Msg.show({
                title: 'Erreur',
                msg: msg,
                buttons: Ext.Msg.OK,
                icon: Ext.window.MessageBox.QUESTION
            });
        }
    }
});

Ext.define('Ext.ia.grid.column.Date', {
    extend:'Ext.grid.column.Date',
    alias: 'widget.ia-datecolumn',
    format: 'd.m.Y'
});

/* TODO:FIXME: default grid action columns
Ext.define('Ext.ia.grid.column.Action', {
    extend:'Ext.grid.column.Action',
    alias: 'widget.ia-actioncolumn',
    config: {
        resource: null,
    },
    width: 25,
    header: 'Détails',
    initComponent: function() {
        this.items = [{
            // TODO: Use a URL in the icon config
            icon: x.context.baseuri+'/a/img/ext/page_white_magnify.png',
            text: 'Détails',
            tooltip: 'Détails',
            handler: function(gridView, rowIndex, colIndex, item) {
                var grid = this.up('gridpanel'),
                    record = grid.store.getAt(rowIndex),
                    id = record.get(record.idProperty);
                if (record.phantom) {
                    Ext.Msg.show({
                        title: 'Erreur',
                        msg: "Veuillez d'abord remplir tous les champs de cette commission",
                        buttons: Ext.Msg.OK,
                        icon: Ext.window.MessageBox.WARNING,
                        fn: function() {
                            var column = grid.getColumns()[0];
                            grid.getPlugin('rowediting').startEdit(record, column);
                        }
                    });
                    return;
                }
                location.href = x.context.baseuri+'/'+this.resource+'/'+id;
            }
        }];
        var me = this;
        me.callParent();
    }
});
*/


Ext.define('Ext.ia.grid.ComboColumn', {
    extend:'Ext.grid.Column',
    alias: 'widget.ia-combocolumn',
    initComponent: function() {
        var me = this;
        me.callParent();
        // Refreshes grid on store load in order to apply the renderer function
        var editor = this.editor || this.field
            store = editor.store;
        store.on('load', function() { me.up('gridpanel').getView().refresh() });
        // Manages store autoloading
        if (!store.autoLoad && !store.loaded && !store.isLoading()) {
            store.load();
        }
    },
    renderer: function(value, metaData, record, rowIndex, colIndex, store) {
        var column = this.columns[colIndex],
            editor = column.editor || column.field,
            comboStore = editor.store,
            displayField = editor.displayField;
        return comboStore.getById(value) ? comboStore.getById(value).get(displayField) : ['(', value, ')'].join('');
    }
});

Ext.define('Ext.ia.form.field.ComboBox', {
    extend:'Ext.form.field.ComboBox',
    alias: 'widget.ia-combo',
    minChars: 1,
    typeAhead: true,
    triggerAction: 'all',
    lazyRender: true,
    initComponent: function() {
        var me = this;
        me.callParent();
        // Store onload value refresh (bugfix)
        var store = this.store;
        store.on('load', function() { me.setValue(me.getValue()) });
        // Manages store autoloading
        if (!store.autoLoad && !store.loaded && !store.isLoading()) {
            store.load();
        }
    }
});

Ext.define('Ext.ia.form.field.Date', {
    extend:'Ext.form.field.Date',
    alias: 'widget.ia-datefield',
    format: 'd.m.Y',
    altFormats: 'd.m.Y|d-m-Y|d m Y',
    startDay: 1
});

/**
 * Draft version of a multifield field
 */
Ext.define('Ext.ia.ux.form.field.Multi', {
    extend: 'Ext.form.FieldContainer',
    alias: 'widget.ia-multifield',
    // Config
    itemType: 'ia-datefield',
    itemMin: 1,
    itemMax: null,
    itemField: null,
    store: null,
    //
    initComponent: function() {
        this.items = [{
            xtype: 'fieldcontainer',
            items: []
        },{
            xtype: 'button',
            id: 'ia-multifield-add-button',
            text: '+',
            handler: function() {
                this.up().addField();
            }
        }];
        //
        var me = this;
        me.callParent();
        //
        if (this.store) this.initStore();
        else for (var i=this.getFieldsCount(); i<this.itemMin; i++) this.addField();
    },
    initStore: function() {
        if (!this.store) return;
        var me = this;
        this.store.on('load', function() {
            this.each(function(record) {
                me.addField(record);
            });
        });
    },
    createItem: function(record) {
        var value = record ? record.get(this.itemField) : null;
        return {
            xtype: this.itemType,
            name: 'FIXME', //FIXME
            value: value,
            _record: record
        };
    },
    // TODO: Attach an onChange event in order to store the changed record
    addField: function(record) {
        var me = this,
            container = this.down('fieldcontainer'),
            count = this.getFieldsCount();
        // Manages store
        // TODO: create a new dirty record and attach it to the field
        //       as a _record property
        // Adds field UI
        if (!this.itemMax || count < this.itemMax) {
            var item = {
                xtype: 'fieldcontainer',
                layout: 'hbox',
                width: 300, //FIXME: how to guess width? from container?
                items: [this.createItem(record), {
                    xtype: 'button',
                    text: '-',
                    handler: function(button) {
                        me.removeField(button);
                    }
                }]
            };
            container.add(item);
        }
        this.toggleControls();
    },
    removeField: function(button) {
        var field = button.up(),
            widget = this,
            count = widget.getFieldsCount();
        // Manages store
        // TODO: remove record from store
        //var record = field.items.items[0]._record;
        // Removes field UI
        if (count > widget.itemMin) field.destroy();
        widget.toggleControls();
    },
    getFieldsCount: function() {
        return this.down('fieldcontainer').items.getCount();
    },
    toggleControls: function() {
        var items = this.down('fieldcontainer').items,
            count = this.getFieldsCount();
        // Manages add button
        var add_button = Ext.getCmp('ia-multifield-add-button');
        if (!this.itemMax || count < this.itemMax) {
            add_button.show();
        } else {
            add_button.hide();
        }
        // Manages del button
        var del_buttons = [];
        items.each(function(item) { del_buttons.push(item.down('button')) });
        if (count > this.itemMin) {
            Ext.each(del_buttons, function(button) { button.show() });
        } else {
            Ext.each(del_buttons, function(button) { button.hide() });
        }
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
             store: null,
             params: {}
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
            }
        }];
        var me = this; me.callParent();
        // Sets store to autoSync changes
        this.store.autoSync = true;
        // Sets grid params to store baseParams
        this.store.proxy.extraParams = this.grid.params;
        // Manages store autoloading
        if (!this.store.autoLoad && !this.store.loaded) this.store.load();
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
        columns: null
    },
    pageSize: 10,
    plugins: [new Ext.grid.plugin.RowEditing({pluginId:'rowediting'})],
    dockedItems: [],
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
        this.dockedItems = this.makeDockedItems();
        var me = this;
        me.callParent();
        this.store.pageSize = this.pageSize;
        this.store.autoSync = true;
        this.store.load();
    },
    makeDockedItems: function() {
        return [{
            xtype: 'toolbar',
            items: [{
                text: 'Ajouter',
                iconCls: 'icon-add',
                handler: this.addItem
            }, '-', {
                text: 'Supprimer',
                iconCls: 'icon-delete',
                handler: this.removeItem
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
        }];
    },
    //TODO: put the Add button logic here, for it to be overridable
    addItem: function() {
        var grid = this.up('gridpanel');
        grid.store.autoSync = false;
        grid.store.insert(0, new grid.store.model());
        grid.store.autoSync = true;
        grid.getPlugin('rowediting').startEdit(0, 0);
    },
    //TODO: put the Remove button logic here, for it to be overridable
    removeItem: function() {
        var grid = this.up('gridpanel');
        var selection = grid.getView().getSelectionModel().getSelection()[0];
        if (selection) {
            grid.store.remove(selection);
        }
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
    createNew: false,
    initComponent: function() {
        if (this.store) this.buttons = [{
            text: 'Save',
            handler: function() {
                var form = this.up('form').getForm();
                var store = this.up('form').store;
                if (form.isValid()) {
                    // Saves record updated values and
                    // resets form values with actual database values
                    form.updateRecord(store.getAt(0));
                    store.getAt(0).save({callback: function(savedRecord) {
                        if (savedRecord) form.loadRecord(savedRecord);
                    }});
                }
            }
        }];
        var me = this; me.callParent();
    },
    listeners: {
        beforerender: function() {
            // Store loading is optional
            if (!this.store) return;
            // New record case
            if (this.createNew) {
                if (this.store.getCount() > 0)
                    Ext.Error.raise("store should be empty");
                this.store.add({});
            }
            // Store autoloading logic
            var me = this;
            if (this.store.getCount() == 0) {
                this.store.load({
                    params: this.loadParams,
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

Ext.define('Ext.ia.window.Popup', {
    extend: 'Ext.window.Window',
    alias: 'widget.ia-popup',
    width: 850,
    modal: true,
    item: {},
    initComponent: function() {
        this.items = [Ext.apply(this.item, {
            title: null,
            frame: false
        })];
        var me = this;
        me.callParent();
        this.show();
    }
});

/******************************************************************************
 * Business objects
**/

// Models
Ext.define('iafbm.model.Etatcivil', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'},
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/etatscivils',
    }
});
Ext.define('iafbm.model.Genre', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'genre', type: 'string'},
        {name: 'genre_short', type: 'string'},
        {name: 'intitule', type: 'string'},
        {name: 'intitule_short', type: 'string'},
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/genres',
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
        {name: 'actif', type: 'boolean', defaultValue: true}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/personnes',
    }
});
Ext.define('iafbm.model.CommissionMembre', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'personne_id', type: 'int'},
        {name: 'fonction_id', type: 'int'},
        {name: 'commission_id', type: 'int'},
        {name: 'personne_nom', type: 'string'},
        {name: 'personne_prenom', type: 'string'},
        {name: 'titre', type: 'string', defaultValue: 'Prof.'},
        {name: 'actif', type: 'boolean', defaultValue: true}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions-membres',
    }
});
Ext.define('iafbm.model.Candidat', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'commission_id', type: 'int'},
        {name: 'nom', type: 'string'},
        {name: 'prenom', type: 'string'},
        {name: 'genre_id', type: 'int'},
        {name: 'etatcivil_id', type: 'int'},
        {name: 'date_naissance', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'no_avs', type: 'string'},
        {name: 'email', type: 'sting'},
        {name: 'adresse_pro', type: 'sting'},
        {name: 'npa_pro', type: 'sting'},
        {name: 'lieu_pro', type: 'sting'},
        {name: 'pays_pro_id', type: 'int'},
        {name: 'telephone_pro', type: 'sting'},
        {name: 'adresse_pri', type: 'sting'},
        {name: 'npa_pri', type: 'sting'},
        {name: 'lieu_pri', type: 'sting'},
        {name: 'pays_pri_id', type: 'int'},
        {name: 'telephone_pri', type: 'sting'},
        {name: 'nom_display', mapping: 0, convert: function(value, record) {
            return [
                record.get('prenom'),
                record.get('nom'),
                record.get('genre_nom_short')].join(' ');
        }},
        {name: 'actif', type: 'boolean', defaultValue: true}
    ],
    validations: [
        { field: 'nom', type: 'presence' },
        { field: 'prenom', type: 'presence' },
    ],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/candidats',
    }
});
Ext.define('iafbm.model.Commission', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'},
        {name: 'commentaire', type: 'string'},
        {name: 'commission-type_id', type: 'int'},
        {name: 'commission-type_nom', type: 'string'},
        {name: 'commission-type_racine', type: 'string'},
        {name: 'commission-etat_id', type: 'int', defaultValue: 1},
        {name: 'commission-etat_nom', type: 'string'},
        {name: 'section_id', type: 'int'},
        {name: 'section_code', type: 'string'},
        {name: 'actif', type: 'boolean', defaultValue: true},
        {name: '_president', type: 'string'}
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
        {name: 'racine', type: 'string'},
        {name: 'actif', type: 'boolean', defaultValue: true}
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
        {name: 'actif', type: 'boolean', defaultValue: true}
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
        {name: 'actif', type: 'boolean', defaultValue: true},
        {name: 'decision', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'preavis', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'autorisation', type: 'date', dateFormat: 'Y-m-d'},
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
Ext.define('iafbm.model.CommissionCandidatCommentaire', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'actif', type: 'boolean', defaultValue: true},
        {name: 'commission_id', type: 'int'},
        {name: 'commentaire', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions-candidats-commentaires',
    }
});
Ext.define('iafbm.model.CommissionTravail', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'primo_loco', type: 'int'},
        {name: 'secondo_loco', type: 'int'},
        {name: 'tertio_loco', type: 'int'},
        {name: 'commentaire', type: 'string'},
        {name: 'actif', type: 'boolean', defaultValue: true}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions-travails',
    }
});
Ext.define('iafbm.model.CommissionTravailEvenement', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'commission_id', type: 'int'},
        {name: 'commission-travail-evenement-type_id', type: 'int'},
        {name: 'date', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'proces_verbal', type: 'boolean'},
        {name: 'actif', type: 'boolean', defaultValue: true}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions-travails-evenements',
    }
});
Ext.define('iafbm.model.CommissionTravailEvenementType', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions-travails-evenements-types',
    }
});
Ext.define('iafbm.model.CommissionValidation', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'commission_id', type: 'int'},
        {name: 'decanat_date', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'decanat_etat', type: 'int'},
        {name: 'decanat_commentaire', type: 'string'},
        {name: 'dg_date', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'dg_commentaire', type: 'string'},
        {name: 'cf_date', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'cf_etat', type: 'int'},
        {name: 'cf_commentaire', type: 'string'},
        {name: 'cdir_date', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'cdir_etat', type: 'int'},
        {name: 'cdir_commentaire', type: 'string'},
        {name: 'reception_rapport', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'envoi_proposition_nomination', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'commentaire', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions-validations',
    }
});
Ext.define('iafbm.model.CommissionValidationEtat', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions-validations-etats',
    }
});
Ext.define('iafbm.model.CommissionFinalisation', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'commission_id', type: 'int'},
        {name: 'reception_contrat_date', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'reception_contrat_commentaire', type: 'string'},
        {name: 'debut_activite', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'commentaire', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url: x.context.baseuri+'/api/commissions-finalisations',
    }
});

// Stores: creates one store per existing model
for (model in iafbm.model) {
    Ext.define(['iafbm', 'store', model].join('.'), {
        extend: 'Ext.ia.data.Store',
        model: ['iafbm', 'model', model].join('.')
    });
}

// Columns
Ext.ns('iafbm.columns');
iafbm.columns.Personne = [{
    xtype: 'actioncolumn',
    width: 25,
    items: [{
        icon: x.context.baseuri+'/a/img/ext/page_white_magnify.png',  // Use a URL in the icon config
        text: 'Détails',
        tooltip: 'Détails',
        handler: function(grid, rowIndex, colIndex, item) {
            var id = grid.store.getAt(rowIndex).get('id');
            var l = window.location;
            var url = [l.protocol, '//', l.host, '/personnes/', id].join('');
            window.location = url;
        }
    }]
}, {
    header: "Nom",
    dataIndex: 'nom',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
}, {
    header: "Prénom",
    dataIndex: 'prenom',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
}, {
    header: "Adresse",
    dataIndex: 'adresse',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
}, {
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
    xtype: 'ia-combocolumn',
    field: {
        xtype: 'ia-combo',
        displayField: 'nom',
        valueField: 'id',
        store: new iafbm.store.Pays()
    }
}, {
    header: "Date de naissance",
    dataIndex: 'date_naissance',
    flex: 1,
    xtype: 'ia-datecolumn',
    field: {
        xtype: 'ia-datefield'
    }
}];

iafbm.columns.CommissionMembre = [{
    header: "Titre",
    dataIndex: '',
    width: 100,
    field: {
        xtype: 'textfield'
    }
}, {
    header: "Nom",
    dataIndex: 'personne_nom',
    width: 125,
}, {
    header: "Prénom",
    dataIndex: 'personne_prenom',
    width: 125,
}, {
    header: "Service",
    dataIndex: 'undefined',
    flex: 1,
    field: {
        xtype: 'textfield'
    }
}, {
    header: "Fonction",
    dataIndex: 'fonction_id',
    flex: 1,
    xtype: 'ia-combocolumn',
    editor: {
        xtype: 'ia-combo',
        displayField: 'nom',
        valueField: 'id',
        allowBlank: false,
        store: new iafbm.store.CommissionFonction()
    }
}];

iafbm.columns.Candidat = [{
    xtype: 'actioncolumn',
    width: 25,
    items: [{
        icon: x.context.baseuri+'/a/img/ext/page_white_magnify.png',  // Use a URL in the icon config
        text: 'Détails',
        tooltip: 'Détails',
        handler: function(grid, rowIndex, colIndex, item) {
            new Ext.ia.window.Popup({
                title: 'Fiche candidat',
                item: new iafbm.form.Candidat({
                    loadParams: { id: 1 },
                    frame: false,
                    listeners: { beforedestroy: function() { grid.store.load() } }
                })
            });
        }
    }]
}, {
    header: "Commission ID",
    dataIndex: 'commission_id',
    //hidden: true
}, {
    header: "Nom",
    dataIndex: 'nom',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
}, {
    header: "Prénom",
    dataIndex: 'prenom',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
}, {
    header: "Date de naissance",
    dataIndex: 'date_naissance',
    flex: 1,
    xtype: 'ia-datecolumn',
    field: {
        xtype: 'ia-datefield'
    }
}, {
    header: "Genre",
    dataIndex: 'genre_id',
    flex: 1,
    xtype: 'ia-combocolumn',
    editor: {
        xtype: 'ia-combo',
        displayField: 'genre',
        valueField: 'id',
        allowBlank: false,
        store: new iafbm.store.Genre()
    }
}/*, {
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
}*/];

iafbm.columns.Commission = [{
    xtype: 'actioncolumn',
    width: 25,
    header: 'Détails',
    items: [{
        // TODO: Use a URL in the icon config
        icon: x.context.baseuri+'/a/img/ext/page_white_magnify.png',
        text: 'Détails',
        tooltip: 'Détails',
        handler: function(gridView, rowIndex, colIndex, item) {
            var grid = this.up('gridpanel'),
                record = grid.store.getAt(rowIndex),
                id = record.get(record.idProperty);
            if (record.phantom) {
                Ext.Msg.show({
                    title: 'Erreur',
                    msg: "Veuillez d'abord remplir tous les champs de cette commission",
                    buttons: Ext.Msg.OK,
                    icon: Ext.window.MessageBox.WARNING,
                    fn: function() {
                        var column = grid.getColumns()[0];
                        grid.getPlugin('rowediting').startEdit(record, column);
                    }
                });
                return;
            }
            location.href = x.context.baseuri+'/commissions/'+id;
        }
    }]
}, {
    header: "Type",
    dataIndex: 'commission-type_id',
    width: 175,
    xtype: 'ia-combocolumn',
    field: {
        xtype: 'ia-combo',
        displayField: 'nom',
        valueField: 'id',
        allowBlank: false,
        store: new iafbm.store.CommissionType()
    }
}, {
    header: "N°",
    dataIndex: 'id',
    width: 75
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
    xtype: 'ia-combocolumn',
    field: {
        xtype: 'ia-combo',
        displayField: 'code',
        valueField: 'id',
        allowBlank: false,
        store: new iafbm.store.Section()
    }
}, {
    header: "Président",
    dataIndex: '_president',
    width: 150,
}, {
    header: "Etat",
    dataIndex: 'commission-etat_id',
    width: 100,
    xtype: 'ia-combocolumn',
    field: {
        xtype: 'ia-combo',
        displayField: 'nom',
        valueField: 'id',
        allowBlank: false,
        store: new iafbm.store.CommissionEtat()
    }
}];

iafbm.columns.CommissionType = [{
    header: "Nom",
    dataIndex: 'nom',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
}, {
    header: "Racine",
    dataIndex: 'racine',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
}];

// Forms
Ext.define('iafbm.form.Candidat', {
    extend: 'Ext.ia.form.Panel',
    store: Ext.create('iafbm.store.Candidat'),
    loadParams: { id: null },
    model: 'Candidat',
    title: 'Candidat',
    frame: true,
    fieldDefaults: {
        labelAlign: 'right',
        msgTarget: 'side'
    },
    defaults: {
        defaultType: 'textfield',
    },
    items: [{
        xtype: 'fieldset',
        title: 'Candidat',
        items: [{
            fieldLabel: 'Nom',
            emptyText: 'Nom',
            name: 'nom'
        }, {
            fieldLabel: 'Prénom',
            emptyText: 'Prénom',
            name: 'prenom'
        }, {
            xtype: 'ia-combo',
            fieldLabel: 'Genre',
            name: 'genre_id',
            displayField: 'genre',
            valueField: 'id',
            store: new iafbm.store.Genre({})
        }, {
            xtype: 'ia-combo',
            fieldLabel: 'Etat civil',
            name: 'etatcivil_id',
            displayField: 'nom',
            valueField: 'id',
            store: new iafbm.store.Etatcivil({})
        }, {
            xtype: 'numberfield',
            fieldLabel: 'Nombre d\'enfants',
            emptyText: 'Nombre d\'enfants',
            name: 'nombre_enfants',
            minValue: 0
        }, {
            fieldLabel: 'N° AVS',
            emptyText: 'N° AVS',
            name: 'no_avs'
        }]
    }, {
        xtype: 'fieldcontainer',
        layout: 'hbox',
        defaults: {
            flex: 1
        },
        items: [{
            xtype: 'fieldset',
            title: 'Formation supérieure',
            items: [
            ]
        }, {
            xtype: 'splitter',
            flex: 0
        }, {
            xtype: 'fieldset',
            title: 'Position actuelle',
            items: [
            ]
        }]
    }, {
        xtype: 'fieldset',
        title: 'Adresses',
        items: [{
            xtype: 'fieldcontainer',
            layout: 'hbox',
            defaults: {
                fieldDefaults: {
                    labelAlign: 'right',
                    msgTarget: 'side'
                },
                border: false,
                flex: 1,
                defaultType: 'textfield'
            },
            items: [{
                xtype: 'fieldcontainer',
                items: [{
                    xtype: 'displayfield',
                    value: '<b>Professionnelle</b>',
                    labelSeparator: null, fieldLabel: '&nbsp;'
                }, {
                    fieldLabel: 'Adresse',
                    emptyText: 'Adresse',
                    name: 'adresse_pro'
                }, {
                    fieldLabel: 'NPA',
                    emptyText: 'NPA',
                    name: 'npa_pro'
                }, {
                    fieldLabel: 'Lieu',
                    emptyText: 'Lieu',
                    name: 'lieu_pro'
                }, {
                    xtype: 'ia-combo',
                    fieldLabel: 'Pays',
                    name: 'pays_pro_id',
                    displayField: 'nom',
                    valueField: 'id',
                    store: new iafbm.store.Pays({})
                }, {
                    fieldLabel: 'Télépone',
                    emptyText: 'Télépone',
                    name: 'telephone_pro'
                }],
            }, {
                xtype: 'fieldcontainer',
                items: [{
                    xtype: 'displayfield',
                    value: '<b>Privée</b>',
                    labelSeparator: null, fieldLabel: '&nbsp;'
                }, {
                    fieldLabel: 'Adresse',
                    emptyText: 'Adresse',
                    name: 'adresse_pri'
                }, {
                    fieldLabel: 'NPA',
                    emptyText: 'NPA',
                    name: 'npa_pri'
                }, {
                    fieldLabel: 'Lieu',
                    emptyText: 'Lieu',
                    name: 'lieu_pri'
                }, {
                    xtype: 'ia-combo',
                    fieldLabel: 'Pays',
                    name: 'pays_pri_id',
                    displayField: 'nom',
                    valueField: 'id',
                    store: new iafbm.store.Pays({})
                }, {
                    fieldLabel: 'Télépone',
                    emptyText: 'Télépone',
                    name: 'telephone_pri'
                }]
            }, {
                xtype: 'fieldcontainer',
                items: [{
                    xtype: 'displayfield',
                    value: '<b>Electronique</b>',
                    labelSeparator: null, fieldLabel: '&nbsp;'
                }, {
                    fieldLabel: 'Email',
                    emptyText: 'Email',
                    name: 'email'
                }]
            }]
        }]
    }]

});


/******************************************************************************
 * Menu tree
 */

// TODO



/******************************************************************************
 * Application
 */

// TODO