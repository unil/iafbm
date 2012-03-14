/******************************************************************************
 * Ext classes customization
**/

/**
 * i18n
 * TODO: FIXME: Move this in locales.js
 */
Ext.window.MessageBox.prototype.buttonText.yes = 'Oui';
Ext.window.MessageBox.prototype.buttonText.no = 'Non';
Ext.window.MessageBox.prototype.buttonText.ok = 'OK';
Ext.window.MessageBox.prototype.buttonText.cancel = 'Annuler';

/**
 * Quick tips initialization
 */
Ext.onReady(Ext.tip.QuickTipManager.init);

/**
 * Ext.Array.createRange()
 */
Ext.Array.createArrayStoreRange = function(min, max) {
    var array = [];
    for (var i=min; i<=max; i++) {
        array.push([i]);
    }
    return array;
};

/**
 * Additional validation types (vtypes)
 */
Ext.apply(Ext.form.field.VTypes, {
    // Swiss AVS number
    avs: function(v) {
        return /^[\d]{2,4}\.[\d]{2,4}\.[\d]{2,4}\.[\d]{2,4}$/.test(v);
    },
    avsText: 'Ce champs doit être au format xx[xx].xx[xx].xx[xx].xx[xx]',
    // Telephone country code
    telcc: function(v) {
        // Country codes cannot start with 0 and must not be longer than 3 numbers
        return /^[1-9]{1}[0-9]{0,2}$/.test(v);
    },
    telccText: 'Ce champs doit contenir l\'indicatif international (p.ex. 41, 33, 49, ...)'

});

/**
 * Extends Ext.data.Store with
 * - with project default config options
 * - adds a 'params' property that applies to proxy.extraParams
 */
Ext.define('Ext.ia.data.Store', {
    extend:'Ext.data.Store',
    alias: 'store.ia-store',
    pageSize: null,
    remoteSort: true,
    autoLoad: false,
    autoSync: false,
    params: {},
    loaded: false,
    applyParamsToProxy: function() {
        // Fix: this.proxy.extraParams is sometimes set to undefined,
        // which prevents to Ext.apply() this.params to the proxy extraParams.
        // It is therefore needed to ensure that extraParams is an object.
        if (!this.proxy.extraParams) this.proxy.extraParams = {};
        // Ext 3 emulation: applies this.params to this.proxy.extraParams
        this.proxy.extraParams = Ext.apply({}, this.params);
    },
    getNonPristineRecords: function() {
        return Ext.Array.merge(
            this.getNewRecords(),
            this.getUpdatedRecords(),
            this.getRemovedRecords()
        );
    },
    listeners: {
        beforeload: function() { this.applyParamsToProxy() },
        beforesync: function() { this.applyParamsToProxy() },
        beforeprefetch: function() { this.applyParamsToProxy() },
        load: function() { this.loaded = true }
    }
});

/**
 * Extends Ext.data.proxy.Rest with
 * - default start, limit and page parameters
 * - reader and writer configuration
 * - actionMethods configuration
 * - exception handling
 */
Ext.define('Ext.ia.data.proxy.Rest', {
    extend:'Ext.data.proxy.Rest',
    alias: 'proxy.ia-rest',
    type: 'rest',
    limitParam: 'xlimit',
    startParam: 'xoffset',
    sortParam: 'xsort',
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
            // User message
            var actions = {
                create: "l'ecriture",
                read: "la lecture",
                update: "l'écriture",
                destroy: "la suppression"
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

/**
 * Extends Ext.grid.column.Date with
 * - default date format
 */
Ext.define('Ext.ia.grid.column.Date', {
    extend:'Ext.grid.column.Date',
    alias: 'widget.ia-datecolumn',
    format: 'd.m.Y'
});

/**
 * Extends Ext.grid.column.Action with
 * - redirecting to URL
 */
Ext.define('Ext.ia.grid.column.ActionRedirect', {
    extend:'Ext.grid.column.Action',
    alias: 'widget.ia-actioncolumn-redirect',
    // TODO: Use a URL in the icon config
    icon: x.context.baseuri+'/a/img/ext/page_white_magnify.png',
    width: 25,
    text: 'Détails',
    tooltip: 'Détails',
    getLocation: function(record) {return null},
    handler: function(gridView, rowIndex, colIndex, item) {
        var grid = this.up('gridpanel'),
            record = grid.store.getAt(rowIndex),
            id = record.get(record.idProperty);
        // Forces the user to fill fields and save phantom (uncreated) record
        if (record.phantom) {
            Ext.Msg.show({
                title: 'Erreur',
                msg: "Veuillez d'abord remplir tous les champs",
                buttons: Ext.Msg.OK,
                icon: Ext.window.MessageBox.WARNING,
                fn: function() {
                    var column = grid.getColumns()[0];
                    grid.getEditingPlugin().startEdit(record, column);
                }
            });
            return;
        }
        location.href = this.getLocation(grid, record, id);
    }
});

/**
 * Extends Ext.grid.column.Action with
 * - loading form in a popup window
 */
Ext.define('Ext.ia.grid.column.ActionForm', {
    extend:'Ext.grid.column.Action',
    alias: 'widget.ia-actioncolumn-detailform',
    // TODO: Use a URL in the icon config
    icon: x.context.baseuri+'/a/img/ext/page_white_magnify.png',
    width: 25,
    text: 'Détails',
    tooltip: 'Détails',
    form: null,
    closeOnSave: false,
    handler: function(gridView, rowIndex, colIndex, item) {
        var me = this,
            popup = new Ext.ia.window.Popup({
            title: 'Détails',
            item: new this.form({
                frame: false,
                record: me.getRecord(gridView, rowIndex, colIndex, item),
                fetch: me.getFetch(gridView, rowIndex, colIndex, item),
                listeners: {
                    // Closes popup on form save
                    aftersave: function(form, record) {
                        if (me.closeOnSave) popup.close();
                    }
                }
            }),
            // Reloads grid store for updating off-record information
            // eg. related tables' fields
            listeners: {
                beforeclose: function() {
                    gridView.store.load();
                }
            }
        });
    },
    getRecord: function(gridView, rowIndex, colIndex, item) {
        return null;
    },
    getFetch: function(gridView, rowIndex, colIndex, item) {
        var record = gridView.getStore().getAt(rowIndex);
        return {
            model: record.store.model,
            id: record.get('id')
        };
    },
    initComponent: function() {
        this.flex = 0;
        this.width = 20;
        this.sortable = false;
        this.menuDisabled = true;
        this.fixed = true;
        var me = this;
        me.callParent();
    }
});

/**
 * Radio column for Ext.grid.Panel
 * Simply refreshes store on checkchange
 * so that server side changes (unchecking others rows radio field)
 * is reflected into this one.
 */
Ext.define('Ext.ia.grid.RadioColumn', {
    extend:'Ext.ux.CheckColumn',
    alias: 'widget.ia-radiocolumn',
    editable: true,
    initComponent: function() {
        this.addEvents(
            /**
             * @event checkchange
             * Fires when the checked state of a row changes
             * @param {Ext.ux.CheckColumn} this
             * @param {Number} rowIndex The row index
             * @param {Boolean} checked True if the box is checked
             */
            'click'
        );
        //
        var me = this;
        me.callParent();
        this.on('checkchange', this.refresh);
        this.on('click', function() { return Boolean(this.editable) });
    },
    refresh: function(checkcolumn, recordIndex, checked) {
        var store = this.up('gridpanel').getStore();
        // Refreshes the grid by reloading the store
        // in order to show the actual unique selected row
        store.load();
    },
    processEvent: function(type, view, cell, recordIndex, cellIndex, e) {
        if (Ext.Array.contains(['mousedown', 'keyup'], type) || Ext.Array.contains([e.ENTER, e.SPACE], e.getKey())) {
            // Fire an extra 'click' event
            var r = this.fireEvent('click', this, type, view, cell, recordIndex, cellIndex, e);
            // Abort if click handler returns false
            if (r === false) return;
        }
        // Process click
        this.callParent(arguments);
    }
});

/**
 * Extends Ext.grid.Column with
 * - remote store display workaround
 */
Ext.define('Ext.ia.grid.ComboColumn', {
    extend:'Ext.grid.Column',
    alias: 'widget.ia-combocolumn',
    initComponent: function() {
        var me = this;
        me.callParent();
        // Refreshes grid on store load in order to apply the renderer function
        var editor = this.editor || this.field,
            store = editor.store;
        store.on('load', function() {
            var grid = me.up('gridpanel');
            if (grid) grid.getView().refresh();
        });
        // Manages store autoloading
        if (!store.autoLoad && !store.loaded && !store.isLoading()) {
            store.load();
        }
    },
    // Fixes a "bug" on combo columns: when stores loads too late,
    // the valueField value is shown instead of displayField value.
    renderer: function(value, metaData, record, rowIndex, colIndex, store) {
        var column = this.columns[colIndex],
            editor = column.editor || column.field,
            comboStore = editor.store,
            displayField = editor.displayField,
            valueField = editor.valueField,
            record = ('id'==valueField) ?
                comboStore.getById(value) :
                comboStore.findRecord(valueField, value, 0, false, true, true);
        if (!record) return '';
        return record.get(displayField);
    }
});

/**
 * Extends Ext.form.field.ComboBox with
 * - remote store display workaround
 */
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

/**
 * Extends Ext.form.field.TextArea with
 * - height fix on render
 */
Ext.define('Ext.ia.form.field.TextArea', {
    extend:'Ext.form.field.TextArea',
    alias: ['widget.ia-textareafield', 'widget.ia-textarea'],
    initComponent: function() {
        var me = this;
        me.callParent();
        // Workaround: fixes textarea height
        // when field value was set/changed before rendering
        // github issue #10
        if (!this.grow) return;
        var setupInitialHeightFix = function() {
            this.on('afterrender', function() {
                this.setHeight(this.growMax);
                this.setHeight(this.inputEl.getHeight());
            });
            this.un('change', setupInitialHeightFix);
        };
        this.on('change', setupInitialHeightFix);
    },
});

/**
 * Extends Ext.form.field.Date with
 * - default date format
 * - default startDay
 */
Ext.define('Ext.ia.form.field.Date', {
    extend:'Ext.form.field.Date',
    alias: 'widget.ia-datefield',
    format: 'd.m.Y',
    altFormats: 'd.m.Y|d-m-Y|d m Y',
    startDay: 1
});

/**
 * Draft version of a multifield field
Ext.define('Ext.ia.form.field.Multi', {
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
 */

/**
 * Selection grid enables the user to add items from one store into another
 * by searching the source store using an autocomplete list
 * and adding items to the destination store.
 * The developer can write the makeData() conversion function,
 * in order to translate source store record into destination store record.
 * -
 */
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
            autoSync: false,
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
        this.store.autoSync = this.grid.autoSync;
        // Sets grid params to store baseParams
        this.store.params = this.grid.params;
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
                        '  <img src="'+img+'" style="float:left;height:39px;margin-right:5px"/>',
                        '  <h3>{prenom} {nom}</h3>',
                        '  <div>{pays_nom} {[values.pays_nom ? ",":"&nbsp;"]} {pays_code}</div>',
                        '  <div>{[values.date_naissance ? Ext.Date.format(values.date_naissance, "j M Y") : "&nbsp;"]}</div>',
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
                    Ext.defer(this.clearValue, 250, this);
                },
                blur: function() { this.clearValue() }
            }
        });
    }
});

/**
 * Extends Ext.ux.form.SearchField with
 * - events firing: beforesearch, aftersearch and resetsearch
 */
Ext.define('Ext.ia.form.SearchField', {
    extend: 'Ext.ux.form.SearchField',
    alias: 'widget.ia-searchfield',
    paramName: 'xquery',
    initComponent: function() {
        this.addEvents(
            /**
            * @event beforesearch
            * Fires after the search is loaded.
            */
            'beforesearch',
            /**
            * @event aftersearch
            * Fires after the search is displayed.
            */
            'aftersearch',
            /**
            * @event resetsearch
            * Fires after the search is reset.
            */
            'resetsearch'
        );
        //
        var me = this;
        me.callParent(arguments);
    },
    onTrigger1Click : function() {
        // Extra event
        this.fireEvent('resetsearch', this);
        // Modified code for store.params manipulation
        // instead of store.proxy.extraParams
        var me = this,
            store = me.store,
            val;
        if (me.hasSearch) {
            me.setValue('');
            if (store.params) delete store.params[me.paramName];
            store.load();
            me.hasSearch = false;
            me.triggerEl.item(0).setDisplayed('none');
            me.doComponentLayout();
        }
    },
    onTrigger2Click : function() {
        // Extra event
        this.fireEvent('beforesearch', this);
        // Modified code for store.params manipulation
        // instead of store.proxy.extraParams
        var me = this,
            store = me.store,
            value = me.getValue();

        if (value.length < 1) {
            me.onTrigger1Click();
            return;
        }
        // Makes sure store.param is a hashmap
        // FIXME: why is it always reset to NULL ?
        //        (eg. in Personne grid, but not in Commission candidats grid)
//console.log(store.params);
        if (!Ext.isObject(store.params)) store.params = Ext.apply({}, store.params);
//console.log(store.params);
        // Applies store params
        store.params[me.paramName] = value;
//console.log(store.params);
        me.hasSearch = true;
        me.triggerEl.item(0).setDisplayed('block');
        me.doComponentLayout();
        // Data refresh
        store.load();
        // Paging reset (if applicable)
        var paging = this.up('grid').down('pagingtoolbar');
        if (paging) paging.moveFirst()
        // Extra event
        this.fireEvent('aftersearch', this);
    }
});

/**
 * Extends Ext.grid.Panel with
 * - Ext.ia.grid.plugin.RowEditing plugin
 * - Add / Delete buttons
 * - Search field
 * - Paging
 */
Ext.define('Ext.ia.grid.EditPanel', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.ia-editgrid',
    config: {
        width: 880,
        height: 300,
        frame: true,
        store: null,
        columns: null,
        newRecordValues: {},
        searchParams: {}
    },
    autoSync: false,
    editable: true,
    toolbarButtons: ['add', 'delete', 'save', 'search'],
    toolbarLabels: {
        add: 'Ajouter',
        delete: 'Supprimer',
        save: 'Enregistrer les modifications',
        search: 'Rechercher'
    },
    pageSize: 10,
    editingPluginId: null,
    plugins: [],
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
        this.addEvents(
            /**
            * @event beforeload
            * Fires before the record is loaded. Return false to cancel.
            * @param {Ext.ia.grid.EditPanel} this
            */
            'beforeload',
            /**
            * @event beforeload
            * Fires after the record is loaded.
            */
            'load'
        );
        // Dynamic parameters
        if (!this.bbar) this.pageSize = null;
        // Creates Editing plugin (storing its id as grid property)
        this.plugins = [new Ext.ia.grid.plugin.RowEditing({
            pluginId: this.editingPluginId = Ext.id()
        })];
        // Creates docked items (toolbar)
        this.dockedItems = this.makeDockedItems();
        // Initializes Component
        var me = this;
        me.callParent();
        // Manage this.editable state
        this.getEditingPlugin().on('beforeedit', function() {
            return Boolean(me.editable);
        });
        // Manages store loading
        // (on 'afterrender' event so that loading mask can be set on 'beforeload' event)
        this.on({afterrender: function() {
            this.fireEvent('beforeload', this);
            this.store.pageSize = this.pageSize;
            this.store.autoSync = me.autoSync;
            this.store.load({
                callback: function(records, operation) {
                    me.fireEvent('load');
                }
            });
        }});
        // Manages proxy exceptions:
        // Reverts store data on proxy exception
        this.on({afterrender: function() {
            var me = this;
            this.store.getProxy().on({
                exception: function(proxy, response, operation) {
                    if (operation.action == 'destroy') {
                        me.store.removeAll();
                        me.store.removed = [];
                        me.store.load();
                    }
                }
            });
        }});
        // Manages loading message
        this.on({
            beforeload: function() { this.setLoading() },
            load: function() { this.setLoading(false)}
        });
        // Manages controls disablement when version is set
        this.on({afterrender: function() {
            // FIXME: This is dirty because the grid should not
            //        be aware of the ia-combo-version
            var me = this,
                // Finds the ia-combo-version contained in plain forms
                form = this.up('form'),
                form_combo = form ? form.down('ia-combo-version') : null,
                // Finds the ia-combo-version contained in 'commission' forms
                tabpanel = this.up('tabpanel'),
                panel = tabpanel ? tabpanel.up('panel') : null,
                panel_combo = panel ? panel.down('ia-combo-version') : null,
                //
                combo = form_combo || panel_combo;
            if (!combo) return;
            // Manages versioned/unversioned grid controls
            combo.on({changeversion: function(combo, version) {
                // Toggles grid toolbar buttons disablement
                me.down('toolbar').items.each(function(c) {
                    c.setDisabled(Boolean(version))
                });
                // Toggles grid rows editability
                // (restoring initial value afterwards)
                if (typeof(me._editableInit)=='undefined') me._editableInit = me.editable;
                me.editable = version ? false : me._editableInit;
                // Toggles grid columns editability (eg. checkboxes)
                // (restoring initial value afterwards)
                Ext.each(me.columns, function(c) {
                    if (!c.isXType('ia-radiocolumn')) return;
                    if (typeof(c._editableInit)=='undefined') c._editableInit = c.editable;
                    c.editable = version ? false : c._editableInit;
                });
            }});
        }});
        // Fixes incorrect grid layout that occurs
        // occasionaly when grid is too narrow (FIXME)
        this.on({load: function() {
            this.doComponentLayout();
        }});
    },
    makeDockedItems: function() {
        var add = {
            text: this.toolbarLabels.add,
            iconCls: 'icon-add',
            handler: this.addItem,
            scope: this
        };
        var del = {
            text: this.toolbarLabels.delete,
            iconCls: 'icon-delete',
            handler: this.removeItem,
            scope: this,
            // Manages button disable state
            disabled: true,
            listeners: {afterrender: function() {
                var me = this,
                    grid = this.up('grid');
                grid.on('selectionchange', function(view, records) {
                    me.setDisabled(!records.length);
                });
            }}
        };
        var save = {
            text: this.toolbarLabels.save,
            iconCls: 'icon-save',
            handler: this.syncItems,
            scope: this,
            // Manages button disable state
            disabled: true,
            listeners: {afterrender: function() {
                var me = this,
                    store = this.up('grid').getStore();
                var callback = function() {
                    me.setDisabled(!store.getNonPristineRecords().length);
                }
                store.on('add', callback);
                store.on('update', callback);
                store.on('remove', callback);
            }}
        };
        var search = new Ext.ia.form.SearchField({
            store: null,
            emptyText: 'Mots-clés',
            listeners: {
                // Wait for render time so that the grid store is created
                // and ready to be bound to the search field
                beforerender: function() { this.store = this.up('gridpanel').store },
                beforesearch: function() { this.onBeforeSearch() },
                aftersearch: function() { this.onResetSearch() },
                resetsearch: function() { this.onResetSearch() },
            },
            onBeforeSearch: function() {
                // Saves current store params
                this._storeParams = Ext.clone(this.store.params);
                // Applies searchParams to store proxy
                this.store.params = Ext.apply(
                    this.store.params,
                    this.up('gridpanel').searchParams
                );
            },
            onResetSearch: function() {
                this.store.params = this._storeParams;
            }
        });
        // Adds items conditionally
        var items = [];
        if (Ext.Array.contains(this.toolbarButtons, 'add'))
            items.push(add);
        if (Ext.Array.contains(this.toolbarButtons, 'delete'))
            items.push('-', del);
        if (Ext.Array.contains(this.toolbarButtons, 'save'))
            items.push('-', save);
        if (Ext.Array.contains(this.toolbarButtons, 'search'))
            items.push('->', '-', this.toolbarLabels.search, search);
        // Creates and returns the toolbar with its items
        return [{
            xtype: 'toolbar',
            items: items
        }];
    },
    getEditingPlugin: function() {
        return this.getPlugin(this.editingPluginId);
    },
    createRecord: function() {
        return new this.store.model(this.newRecordValues);
    },
    addItem: function() {
        var grid = this,
            autoSync = grid.store.autoSync;
        // Disables autoSync before inserting line
        grid.store.autoSync = false;
        grid.store.insert(0, grid.createRecord());
        // Re-enables autoSync after inserting line (if applicable)
        grid.store.autoSync = autoSync;
        grid.getEditingPlugin().startEdit(0, 0);
    },
    removeItem: function() {
        var grid = this,
            selection = grid.getView().getSelectionModel().getSelection()[0];
        if (selection) grid.store.remove(selection);
    },
    syncItems: function() {
        this.store.sync();
    }
});

/**
 * Extends Ext.grid.plugin.RowEditing with
 * - project-specific config
 * - autoCancel logic (disabled for now)
 */
Ext.define('Ext.ia.grid.plugin.RowEditing', {
    extend: 'Ext.grid.plugin.RowEditing',
    alias: 'plugin.ia-rowediting',
    clicksToEdit: 2,
    clicksToMoveEditor: 1,
    autoCancel: false,
    errorSummary: false,
    constructor: function() {
        var me = this;
        me.callParent(arguments);
        // FIXME: Is it still necessary?
        // Workaround: we need to reset the store.params because surprisingly,
        // they get changed when a row is added to the grid through
        // the RowEditor plugin
        //this.on('edit', function(context) {
        //    context.store.params = context.store.proxy.extraParams;
        //});
        //
        // Workaround for preventing validation errors tooltips to show up.
        // Curse errorSummary is not properly managed
        this.on('beforeedit', function() {
            if (!me.errorSummary) this.editor.showToolTip = function() {};
        });
    },
    // On edit cancel, remove phantom row or reject existing row modifications
    // http://www.sencha.com/forum/showthread.php?130412-OPEN-EXTJSIV-1649-RowEditing-improvement-suggestions
    // NOTE: Managed better in ExtJS 4.0.7
    _cancelEdit: function() {
        if (this.context) {
            var record = this.context.record;
            if (record.phantom) {
                // This _deleting flag based conditional return prevents an infinite loop
                // for store.remove(record) probably indirectly calls
                // this cancelEdit method...
                if (record._deleting) return;
                record._deleting = true;
                this.context.store.remove(record);
            } else {
                record.reject();
            }
        }
        var me = this;
        me.callParent();
    }
});

/*
// BEGIN Form Dirty Management //
var ext_field_override = {
    _dirtyCls: 'x-grid-dirty-cell',
    trackResetOnLoad: true,
    initEvents: function() {
t=this;
        // parent logic application
        //this.callOverridden();
console.debug(this.$className, 'Ext.form.Field.initEvents()', this.el, this);
        // Events
        this.el.on(Ext.isIE ? "keydown" : "keypress", this.fireKey, this);
        this.el.on("focus", this.onFocus, this);
        this.el.on("blur", this.onBlur, this);
        this.on("blur", this.markDirty, this);
        // reference to original value for reset
        //this.originalValue = this.getValue();
    },
    markDirty: function() {
console.debug('Ext.form.Field.markDirty()', '|', this, '|', this.originalValue, '|', this.lastValue, '|', this.getValue());
        if (this.isDirty() && this.originalValue != this.getValue()) {
            if (!this.dirtyIcon) {
                var elp = this.el;
                this.dirtyIcon = elp.createChild({
                    cls: 'x-grid-dirty-cell'
                });
                // IE Hack...
                if (Ext.isIE) this.dirtyIcon.position("absolute", 0, -5, 0);
                else this.dirtyIcon.position("absolute", 0, 0, 0);
                // ...is ugly but acceptable
                this.dirtyIcon.setSize(10, 10);
            }
            this.alignDirtyIcon();
            this.dirtyIcon.show();
            this.on('resize', this.alignDirtyIcon, this);
        } else {
            if (this.dirtyIcon) {
                this.dirtyIcon.hide();
            }
        }
    },
    alignDirtyIcon: function() {
        this.dirtyIcon.alignTo(this.el, 'tl', [0, 0]);
    }
};
Ext.override(Ext.form.field.Base, ext_field_override);
Ext.override(Ext.form.field.Field, {
    resetOriginalValue: function() {
        this.callOverridden();
        console.debug(this.originalValue);
    }
});

Ext.override(Ext.form.BasicForm, {
    clearDirty: function () {
        var i, it = this.items.items,
            l = it.length,
            c;
        for (i = 0; i < l; i++) {
            c = it[i];
            c.markDirty();
            c.originalValue = String(c.getValue());
        }
    }
});
// END Form Dirty Management //
*/

/**
 * FIXME: Test "create version" button
 */
Ext.define('Ext.ia.button.CreateVersion', {
    extend:'Ext.Button',
    alias: 'widget.ia-create-version',
    text:'Créer une version',
    model: null, // model constructor
    createVersion: function() {
        var me = this,
            field = this.menu.down('textfield'),
            comment = field.getValue(),
            record = this.up('form').record,
            id = record.get('id'),
            url = record.proxy.url;
        // Call model url + id + tag xmethod
        if (!field.isValid()) return;
        Ext.Ajax.request({
            url: url,
            params: {
                id: id,
                xmethod: 'tag',
                commentaire: comment
            },
            method: 'GET',
            success: function(xhr) {
                me.hideMenu();
                field.reset();
            }
        });
    },
    initComponent: function() {
        var me = this;
        // Creates button menu
        this.menu = {
            items: [{
                xtype: 'form',
                layout: 'hbox',
                border: false,
                width: 330,
                items: [{
                    xtype: 'textfield',
                    //allowBlank: false, // FIXME: Messes with form validation
                    fieldLabel: 'Commentaire',
                    labelWidth: 75,
                    width: 250
                }, {
                    xtype: 'button',
                    text: 'Créer',
                    flex: 1,
                    handler: function() { me.createVersion() }
                }]
            }],
        };
        // Inits component
        me.callParent();
    },
});

/**
 * Extends combobox for version selection:
 * this is to be used within a form.
 * Does:
 * - sets xversion parameter to the form' store
 * - sets xversion parameter to the form' grids' stores
 */
Ext.define('Ext.ia.form.field.VersionComboBox', {
    extend:'Ext.form.field.ComboBox',
    alias: 'widget.ia-combo-version',
    typeAhead: false,
    editable: false,
    listConfig: {
        getInnerTpl: function() {
            return [
                '<tpl if="id==0">Version actuelle</tpl>',
                '<tpl if="id!=0">{id}</tpl>',
                '<tpl if="commentaire"> - {commentaire}</tpl>',
            ].join('');
        }
    },
    emptyText: 'Version actuelle',
    valueField: 'id',
    displayField: 'id',
    store: null,
    tables: [],
    getTopLevelComponent: function() {
        return this.up('form');
    },
    getComponents: function() {
        var components = [],
            topLevelComponent = this.getTopLevelComponent();
        // Adds top level component (it is often a form)
        topLevelComponent.cascade(function(c) {
            // Adds forms and grids
            if (c.isXType('form') || c.isXType('gridpanel')) components.push(c);
        });
        return components;
    },
    changeVersion: function(version) {
        Ext.each(this.getComponents(), function(c) {
            // Applies version to the forms record
            if (c.isXType('form')) {
                c.loadRecord({xversion:version})
            }
            // Applies version to the form grids
            if (c.isXType('gridpanel')) {
                c.store.params['xversion'] = version;
                c.store.load();
            }
        });
    },
    initComponent: function() {
        this.addEvents([
            /**
            * @event changeversion
            * Fires after a version has been selected
            * @param {Ext.ia.form.field.VersionComboBox} this
            * @param {int} version number
            */
            'changeversion'
        ]);
        //
        this.store = new iafbm.store.Version({
            params: {
                'table_name[]': this.tables,
                //operation: 'tag',
                xorder_by: 'id',
                xorder: 'DESC'
            }
        });
        // Adds a record for current version
        this.store.on({load: function() {
            // Label last version as 'actual version'
            var record = this.getAt(0);
            if (record) record.set({id: 0});
            else this.add({id: 0});
        }});
        //
        var me = this;
        me.callParent();
        //
        this.on({
            select: function(combo, records) {
                var record = records.shift(),
                    version = record.get('id');
                this.changeVersion(version);
                this.fireEvent('changeversion', me, version);
                // Sets templated text in combo
                var display = version ?
                    Ext.String.format("Version {0}", version) :
                    "Version actuelle";
                this.setRawValue(display);
            },
            expand: function() {
                this.store.load();
            }
        });
    }
});

/**
 * Extends Ext.form.Panel with
 * - record loading/saving capabilities
 * - TODO: dirty fields management
 */
Ext.define('Ext.ia.form.Panel', {
    extend: 'Ext.form.Panel',
    alias: 'widget.ia-form',
    autoHeight: true,
    bodyPadding: 10,
    border: 0,
    defaults: {
        //anchor: '100%',
        msgTarget: 'side',
        defaultType: 'textfield'
    },
    fieldDefaults: {
        labelWidth: 80,
        labelAlign: 'right',
        msgTarget: 'side'
    },
    record: null,
    fetch: {
        model: null,
        id: null,
        params: {}
    },
    dockedItems: [],
    getRecordId: function() {
        return this.fetch.id || this.record.get('id');
    },
    makeRecord: function() {
        // The fetch property can contain either a regular Ext.data.Model
        // or a configuration object containing the model and the id to load
        if (this.record) {
            this.fireEvent('beforeload', this);
            this.getForm().loadRecord(this.record);
            this.fireEvent('load', this, this.record);
        } else {
            this.loadRecord();
        }
    },
    loadRecord: function(params) {
        var params = params || {};
        if (this.fetch && this.fetch.model && this.fetch.model.load) {
            this.fireEvent('beforeload', this);
            // Manages proxy parameters
            // because we have a model but no store to deal with:
            // Saves pristine proxy parameters
            // before applying specific fetch parameters
            // (pristine parameters are restored in response callback)
            var proxy = this.fetch.model.proxy;
            var proxyExtraParams = Ext.clone(proxy.extraParams);
            proxy.extraParams = Ext.apply({}, this.fetch.params, params);
            // Creates the record
            var me = this;
            this.fetch.model.load(this.fetch.id, {
                success: function(record) {
                    // Checks record before applying
                    if (!record) {
                        proxy.fireEvent('exception', proxy, {}, {action:'read'});
                        return;
                    }
                    // Load record into form
                    me.record = record;
                    me.getForm().loadRecord(me.record);
                    me.fireEvent('load', me, me.record);
                },
                callback: function() {
                    if (proxy) proxy.extraParams = proxyExtraParams;
                }
            });
        }
    },
    saveRecord: function() {
        if (this.fireEvent('beforesave', this, record) === false) return;
        var me = this,
            record = this.getRecord();
        //TODO: would it be clever to reuse the record validation be used here?
        if (!this.getForm().isValid()) return;
        // Updates record from form values
        // FIXME: updateRecord() will trigger the save action
        //        if the record belongs to Store with autoSync,
        //        which will trigger the POST request twice :(
        this.getForm().updateRecord(record);
        record.save({ success: function(record, operation) {
            if (!operation.success) return;
            me.fireEvent('aftersave', me, record);
            me.getForm().loadRecord(record);
        }});
        // Syncs stores of contained grids
        this.cascade(function (c) {
            if (c.isXType('gridpanel')) c.store.sync();
        })
    },
    isDirtyWhole: function() {
        // Manages not synced stores
        // when user quits the form (popup close or page change)
        // displays a dialog.
        var changes = 0;
        // Checks form changes
        var record = this.record;
        if (!record) return false;
        this.form.getFields().each(function(f) {
            // Skips ia-combo-version fields
            if (f.isXType('ia-combo-version')) return;
            // Skips fields that do not exist in record
            if (!Ext.Array.contains(record.fields.keys, f.name)) return;
            // Skips not-modified fields
            if (record.get(f.name) == f.getValue()) return;
            if (record.get(f.name)==null && f.getValue()=='') return; // record.get(...) sometimes returns null
            if (record.get(f.name).toString() == f.getValue().toString()) return;
            // Adds one more change
            changes += 1;
        });
        // Checks contained grids changes
        this.cascade(function(c) {
            // Skips if not a gridpanel
            if (!c.isXType('gridpanel')) return;
            // Adds changes
            changes += Ext.Array.merge(
                c.store.getNewRecords(),
                c.store.getUpdatedRecords(),
                c.store.getRemovedRecords()
            ).length;
        });
        return changes;
    },
    initComponent: function() {
        this.addEvents(
            /**
            * @event beforeload
            * Fires before the record is loaded.
            * @param {Ext.ia.form.Panel} this
            */
            'beforeload',
            /**
            * @event load
            * Fires after a record is loaded.
            * @param {Ext.ia.form.Panel} this
            * @param {Ext.data.Model} record The {@link Ext.data.Model} to be saved
            */
            'load',
            /**
            * @event aftersave
            * Fires before the record is saved. Return false to cancel the sync.
            * @param {Ext.ia.form.Panel} this
            * @param {Ext.data.Model} record The {@link Ext.data.Model} to be saved
            */
            'beforesave',
            /**
            * @event aftersave
            * Fires after the record has been saved.
            * @param {Ext.ia.form.Panel} this
            * @param {Ext.data.Model} savedRecord The saved {@link Ext.data.Model}
            */
            'aftersave'
        );
        // If applicable, create a save button for the form
        if (this.record || this.fetch.model) {
            if (!this.tbar) this.tbar = [];
            this.tbar.push({
                xtype: 'button',
                text: 'Enregistrer',
                iconCls: 'icon-save',
                scale: 'medium',
                handler: function() { me.saveRecord() },
                initComponent: function() {
                    var me = this;
                    me.callParent();
                    // Disables button if a version is selected
                    this.on({afterrender: function() {
                        // FIXME: This is dirty because the grid should not
                        //        be aware of the ia-combo-version
                        var me = this,
                            // Finds the ia-combo-version contained in plain forms
                            form = this.up('form'),
                            form_combo = form ? form.down('ia-combo-version') : null,
                            // Finds the ia-combo-version contained in 'commission' forms
                            tabpanel = this.up('tabpanel'),
                            panel = tabpanel ? tabpanel.up('panel') : null,
                            panel_combo = panel ? panel.down('ia-combo-version') : null,
                            //
                            combo = form_combo || panel_combo;
                        if (!combo) return;
                        combo.on({changeversion: function(combo, version) {
                            me.setDisabled(version);
                            // Toggles form fields to 'readonly' mode
                            // TODO: Move this logic into actual form
                            me.up('form').cascade(function(c) {
                                if (c.isXType('field') && !c.isXType('ia-combo-version'))
                                    c.setReadOnly(version);
                            });
                        }});
                    }});
                },
            });
        }
        var me = this;
        me.callParent();
        // Manages record loading
        this.on('afterrender', function() {
            this.makeRecord();
        });
    }
});

/* This tabpanel is used for the Commission details:
 * it manages:
 * - styling its tabs according its contained form record 'termine' field value
 * - firing the loading of its contained forms records
 */
Ext.define('Ext.ia.tab.CommissionPanel', {
    extend: 'Ext.tab.Panel',
    alias: 'widget.ia-tabpanel-commission',
    updateTabState: function(tab) {
        var tab = tab || this.getActiveTab(),
            finished = tab.down('ia-form-commission').record.get('termine');
        // Determines tab CSS class
        var cls = finished ? 'tab-icon-done' : 'tab-icon-pending';
        tab.setIconCls(cls);
    },
    initComponent: function() {
        var me = this;
        me.callParent();
        // For each tab, update its visual state on the form load event
        this.on('afterrender', function() {
            this.items.each(function(tab) {
                var form = tab.down('ia-form-commission');
                form.on({load: function() {
                    me.updateTabState(tab);
                }});
                form.makeRecord();
            });
        });
        // Displays a changes-not-saved message
        this.on('tabchange', function(tabPanel, newCard, oldCard) {
            if (oldCard.down('form').isDirtyWhole()) {
                var me = this,
                    title = "Modifications non enregistrées",
                    message = "L'onglet contient des modifications non enregistrées. \
                        Enregistrer ces modifications? \
                        <br/><br/> \
                        <b>Oui:</b> Enregistrer les modifications <br/> \
                        <b>Non:</b> Abondonner les modifications <br/> \
                        <b>Annuler:</b> Rester sur cet onglet";
                Ext.Msg.show({
                    title: title,
                    msg: message,
                    buttons: Ext.Msg.YESNOCANCEL,
                    icon: Ext.Msg.QUESTION,
                    fn: function(is) {
                        switch (is) {
                            case 'yes':
                                oldCard.down('ia-form').saveRecord();
                                break;
                            case 'no':
                                oldCard.down('ia-form').loadRecord();
                                break;
                            case 'cancel':
                            default:
                                me.setActiveTab(oldCard);
                                break;
                        }
                    }
                });
            }
        });
    }
});

/* This form is used for the Commission details:
 * it manages:
 * - creating a checkbox for 'termine' field value
 * - styling the checkbox panel according the 'termine' field value
 * - updating its tabpanel container according the 'termine' field value
 */
Ext.define('Ext.ia.form.CommissionPhasePanel', {
    extend: 'Ext.ia.form.Panel',
    alias: 'widget.ia-form-commission',
    bodyCls: 'x-ia-panel-commission',
    dockedItems: [],
    phasesCls: {
        pending: 'x-ia-toolbar-pending',
        finished: 'x-ia-toolbar-done'
    },
    onCheckboxClick: function(checkbox) {
        // Updates dans saves record
        this.record.set('termine', checkbox.checked);
        this.record.save();
        // Updates form state display
        this.updateCheckboxState();
        // Updates related tab state display
        var tabpanel = this.up('ia-tabpanel-commission');
        tabpanel.updateTabState();
    },
    updateCheckboxState: function() {
        var finished = this.record.get('termine');
        // Sets checkbox value
        var checkbox = this.getDockedComponent(0).getComponent('checkbox-finished');
        checkbox.setValue(finished);
        // Sets top toolbar style
        var state = finished ? 'finished' : 'pending',
            cls = this.phasesCls[state];
        var toolbar = this.getDockedComponent(0);
        for (var i in this.phasesCls) toolbar.removeCls(this.phasesCls[i]);
        toolbar.addCls(cls);
    },
    makeDockedItems: function() {
        return ['->', {
            xtype: 'checkbox',
            itemId: 'checkbox-finished',
            boxLabel: 'Phase terminée',
            handler: function(checkbox, checked) {
                // Aborts if checkbox state is the same as the record state
                // This prevents POSTing the record
                // when ExtJS sets the checkbox initial value
                var finished = this.up('ia-form-commission').record.get('termine');
                if (finished == checked) return;
                // Fires checkbox change logic
                this.up('form').onCheckboxClick(checkbox);
            }
        }];
    },
    initComponent: function() {
        this.dockedItems = [{
            xtype: 'toolbar',
            items: this.makeDockedItems()
        }];
        //
        var me = this;
        me.callParent();
        // Updates form state on record load
        this.on({load: this.updateCheckboxState});
    }
});

/**
 * Extends Ext.window.Window with
 * - default options: size, modal
 * - TODO: dirty fields management
 */
Ext.define('Ext.ia.window.Popup', {
    extend: 'Ext.window.Window',
    alias: 'widget.ia-popup',
    width: 1000,
    y: 10,
    autoShow: true,
    autoScroll: true,
    modal: true,
    item: {},
    initComponent: function() {
        this.items = [Ext.apply(this.item, {
            title: null,
            frame: false
        })];
        //
        var me = this;
        me.callParent();
        // Displays a changes-not-saved message
        this.on('beforeclose', function() {
            // Determines unsaved changes
            var dirty = false;
            this.cascade(function(c) {
                if (!c.isXType('ia-form')) return;
                if (c.isDirtyWhole()) dirty = true;
            });
            if (!dirty) return;
            var title = "Modifications non enregistrées",
                message = "La fenêtre contient des modifications non enregistrées. \
                    Enregistrer ces modifications? \
                    <br/><br/> \
                    <b>Oui:</b> Enregistrer les modifications <br/> \
                    <b>Non:</b> Abondonner les modifications <br/> \
                    <b>Annuler:</b> Rester sur cette fenêtre";
            Ext.Msg.show({
                title: title,
                msg: message,
                buttons: Ext.Msg.YESNOCANCEL,
                icon: Ext.Msg.QUESTION,
                fn: function(is) {
                    switch (is) {
                        case 'yes':
                            me.down('ia-form').saveRecord();
                            me.close();
                            break;
                        case 'no':
                            me.suspendEvents();
                            me.close();
                            me.resumeEvents();
                            break;
                        case 'cancel':
                        default:
                            break;
                    }
                }
            });
            return false;
        });
    }
});


/******************************************************************************
 * History
 */
/*
Ext.define('Ext.ia.ux.grid.History', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.ia-history',
    store: new iafbm.store.VersionData({
        //FIXME:
        // + dynamic commission_id
        // + let history make clever queries (eg. add commission_id = ?)
        //params: { commission_id: 2 },
        sorters: [{property: 'version_id', direction: 'DESC'}],
        groupField: 'version_id',
        autoLoad: true
    }),
    features: [
        Ext.create('Ext.grid.feature.Grouping', {
            groupHeaderTpl: 'Version {name} ({rows.length})',
            //startCollapsed: true
        })
    ],
    title: 'Historique',
    columns: [{
        header: 'Modèle',
        dataIndex: 'version_model_name',
        width: 125
    }, {
        header: 'Version',
        dataIndex: 'version_id',
        width: 30
    }, {
        header: 'Date',
        dataIndex: 'version_created',
        //xtype: 'ia-datecolumn',
        width: 110
    }, {
        header: 'Utilisateur',
        dataIndex: 'user',
        width: 100
    }, {
        header: 'Champs',
        dataIndex: 'field_name',
        width: 100
    }, {
        header: 'Ancienne valeur',
        dataIndex: 'old_value',
        flex: 1
    }, {
        header: 'Nouvelle valeur',
        dataIndex: 'new_value',
        flex: 1
    }]
});
*/