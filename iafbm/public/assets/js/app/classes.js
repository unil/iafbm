/******************************************************************************
 * Ext classes customization
**/

/**
 * Additional validation types (vtypes)
 */
Ext.apply(Ext.form.field.VTypes, {
    avs: function(v) {
        return /^[\d]{3}\.[\d]{4}\.[\d]{4}\.[\d]{2}$/.test(v);
    },
    avsText: 'Ce champs doit être au format xxx.xxxx.xxxx.xx'
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
    autoLoad: false,
    autoSync: false,
    params: {},
    loaded: false,
    listeners: {
        beforeload: function() { this.proxy.extraParams = Ext.apply(this.proxy.extraParams, this.params) },
        load: function() { this.loaded = true }
    },
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
                create: "l'ecriture",
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
 * - loading form in a popup window
 */
Ext.define('Ext.ia.grid.column.Action', {
    extend:'Ext.grid.column.Action',
    alias: 'widget.ia-actioncolumn-detailform',
    icon: x.context.baseuri+'/a/img/ext/page_white_magnify.png',
    text: 'Détails',
    tooltip: 'Détails',
    form: null,
    handler: function(gridView, rowIndex, colIndex, item) {
        var me = this,
            popup = new Ext.ia.window.Popup({
            title: 'Détails',
            item: new me.form({
                frame: false,
                record: me.getRecord(gridView, rowIndex, colIndex, item),
                fetch: me.getFetch(gridView, rowIndex, colIndex, item),
                listeners: {
                    aftersave: function(form, record) {
                        popup.close();
                        // Reloads store for refreshing gridview
                        // external values (such as listcolumns)
                        // TODO: also du this on popup close
                        gridView.refresh();
                    }
                }
            })
        });
    },
    getRecord: function(gridView, rowIndex, colIndex, item) {
        return gridView.getStore().getAt(rowIndex);
    },
    getFetch: function(gridView, rowIndex, colIndex, item) {
        return {};
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
 * Displays a list of this.displayField values contained in this.store,
 * filtered by this.filterField using this.dataIndex field vaulue,
 * separated by this.separator.
 */
Ext.define('Ext.ia.grid.ListColumn', {
    extend:'Ext.grid.Column',
    alias: 'widget.ia-listcolumn',
    // Config
    store: null,
    filterField: null,
    displayField: null,
    separator: ', ',
    initComponent: function() {
        var me = this,
            store = this.store;
        me.callParent();
        // TODO: is this necessary on store load?
        store.on('load', function() { me.up('gridpanel').getView().refresh() });
        // TODO: on this.up('gridpanel').getView() refresh event, also refresh this field!
        // Manages store autoloading
        if (!store.autoLoad && !store.loaded && !store.isLoading()) {
            store.load();
        }
    },
    renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
        var me = this.columns[colIndex],
            store = me.store,
            values = [];
        if (me.filterField) store.filter(me.filterField, value);
        Ext.each(store.getRange(), function(record) {
            values.push(record.get(me.displayField))
        });
        if (me.filterField) store.clearFilter();
        return values.join(me.separator);
    }
});

/**
 * Radio column for Ext.grid.Panel
 * WARNING (!): will not work properly on paged grids since it
 *              only unchecks items *loaded* in store
 * TODO: Render the widget as a radio style button
 */
Ext.define('Ext.ia.grid.RadioColumn', {
    extend:'Ext.ux.CheckColumn',
    alias: 'widget.ia-radiocolumn',
    initComponent: function() {
        var me = this;
        me.callParent();
        this.on('checkchange', this.uncheckOthers);
    },
    // Unchecks all others checkbox with the same dataIndex within the grid
    uncheckOthers: function(checkcolumn, recordIndex, checked) {
        var dataIndex = this.dataIndex,
            store = this.up('gridpanel').getStore();
        store.each(function(record) {
            // Skips the click radio row
            if (record == store.getAt(recordIndex)) return;
            // Update not needed if value is already false
            if (!record.get(dataIndex)) return;
            record.set(dataIndex, false);
        });
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
        var editor = this.editor || this.field
            store = editor.store;
        store.on('load', function() { me.up('gridpanel').getView().refresh() });
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
            displayField = editor.displayField;
        return comboStore.getById(value) ? comboStore.getById(value).get(displayField) : '';
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

/**
 * Extends Ext.grid.Panel with
 * - Ext.grid.plugin.RowEditing plugin
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
        newRecordValues: {}
    },
    editable: true,
    toolbarButtons: ['add', 'delete'],
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
        // Creates docked items (toolbar)
        this.dockedItems = this.makeDockedItems();
        // Creates Editing plugin
        if (this.editable) {
            this.editingPluginId = Ext.id();
            this.plugins = [new Ext.grid.plugin.RowEditing({
                pluginId: this.editingPluginId
            })];
        }
        // Initializes Component
        var me = this;
        me.callParent();
        // Manages store loading
        // (on 'afterrender' event so that loading mask can be set on 'beforeload' event)
        this.on({afterrender: function() {
            this.fireEvent('beforeload', this);
            this.store.pageSize = this.pageSize;
            this.store.autoSync = true;
            this.store.load({
                callback: function(records, operation) {
                    me.fireEvent('load');
                }
            });
        }});
        // Manages loading message
        this.on({
            beforeload: function() { this.setLoading() },
            load: function() { this.setLoading(false)}
        });
    },
    getEditingPlugin: function() {
        return this.getPlugin(this.editingPluginId);
    },
    makeDockedItems: function() {
        var add = {
            text: 'Ajouter',
            iconCls: 'icon-add',
            handler: this.addItem
        };
        var del = {
            text: 'Supprimer',
            iconCls: 'icon-delete',
            handler: this.removeItem
        };
        var search = new Ext.ux.form.SearchField({
            store: null,
            emptyText: 'Mots-clés',
            listeners: {
                // Wait for render time so that the grid store is created
                // and ready to be bound to the search field
                beforerender: function() { this.store = this.up('gridpanel').store }
            }
        });
        // Adds items conditionally
        var items = [];
        if (Ext.Array.contains(this.toolbarButtons, 'add')) items.push(add);
        if (Ext.Array.contains(this.toolbarButtons, 'delete')) items.push('-', del);
        items.push('->', '-', 'Rechercher', search);
        // Creates and returns the toolbar with its items
        return [{
            xtype: 'toolbar',
            items: items
        }];
    },
    addItem: function() {
        var grid = this.up('gridpanel'),
            autoSync = grid.store.autoSync;
        grid.store.autoSync = false;
        grid.store.insert(0, grid.createRecord());
        grid.store.autoSync = autoSync;
        grid.getEditingPlugin().startEdit(0, 0);
    },
    removeItem: function() {
        var grid = this.up('gridpanel');
        var selection = grid.getView().getSelectionModel().getSelection()[0];
        if (selection) {
            grid.store.remove(selection);
        }
    },
    createRecord: function() {
        return new this.store.model(this.newRecordValues);
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
console.log(this.$className, 'Ext.form.Field.initEvents()', this.el, this);
        // Events
        this.el.on(Ext.isIE ? "keydown" : "keypress", this.fireKey, this);
        this.el.on("focus", this.onFocus, this);
        this.el.on("blur", this.onBlur, this);
        this.on("blur", this.markDirty, this);
        // reference to original value for reset
        //this.originalValue = this.getValue();
    },
    markDirty: function() {
console.log('Ext.form.Field.markDirty()', '|', this, '|', this.originalValue, '|', this.lastValue, '|', this.getValue());
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
        console.log(this.originalValue);
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
        msgTarget: 'side'
    },
    fieldDefaults: {
        labelWidth: 80
    },
    record: null,
    fetch: {
        model: null,
        id: null
    },
    getRecordId: function() {
        return this.fetch.id || this.record.get('id');
    },
    makeRecord: function() {
        // The fetch property can contain either a regular Ext.data.Model
        // or a configuration object containing the model and the id to load
        if (this.record) {
            this.fireEvent('beforeload', this);
            this.getForm().loadRecord(this.record);
        } else if (this.fetch && this.fetch.model && this.fetch.model.load) {
            this.fireEvent('beforeload', this);
            var proxy = this.fetch.model.proxy;
            // Manages parameters
            if (this.fetch.params) {
                var proxyExtraParams = Ext.clone(proxy.extraParams);
                proxy.extraParams = Ext.apply(proxy.extraParams, this.fetch.params);
            }
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
                    // Fires a quite useful event
                    me.fireEvent('load');
                },
                failure: function(record) {},
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
        if (this.getForm().isValid()) {
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
        }
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
        if (this.record || this.fetch.model) this.buttons = [{
            text: 'Save',
            handler: function() { me.saveRecord() }
        }];
        var me = this;
        me.callParent();
        // Manages record loading
        this.addListener('afterrender', function() {
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
        this.on({afterrender: function() {
            this.items.each(function(tab) {
                var form = tab.down('ia-form-commission');
                form.on({load: function() {
                    me.updateTabState(tab);
                }});
                form.makeRecord();
            });
        }});
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
    phases: {
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
            cls = this.phases[state];
        var toolbar = this.getDockedComponent(0);
        for (var i in this.phases) toolbar.removeCls(this.phases[i]);
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
    width: 850,
    y: 90,
    autoScroll: true,
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