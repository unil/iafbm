/******************************************************************************
 * Ext classes customization
**/
Ext.ns('Ext.ia');

/**
 * Quick tips initialization
 */
Ext.onReady(Ext.tip.QuickTipManager.init);

/**
 * Common captions.
 * Used for:
 * - Notifying of errors HTTP Requests error (according the HTTP status)
 */
Ext.ia.caption = {
    status: {
        // FIXME: Not used for now: use it or remove it.
        200: 'OK',
        400: 'Requête malformée',
        401: 'Accès non autorisé',
        403: 'Accès non autorisé',
        404: 'Element introuvable',
        405: 'Accès non autorisé',
        408: 'Délai expiré',
        500: 'Erreur inopinée'
    },
    // Used for notifications css classnames construction
    type: {
        200: 'confirm',
        400: 'error',
        401: 'denied',
        403: 'denied',
        404: 'notfound',
        405: 'denied',
        408: 'error',
        500: 'error'
    },
    // Used for notifications titles
    titles: {
        create: 'Ajout',
        read: 'Lecture',
        update: 'Modification',
        delete: 'Suppression',
        //
        200: 'effectué(e)',
        400: 'impossible (invalide)',
        401: 'non autorisé(e)',
        403: 'non autorisé(e)',
        404: 'impossible',
        405: 'non autorisé(e)',
        500: 'impossible (erreur système)'
    },
    // Used for notifications text content
    texts: {
        200: "Succès pour",
        401: "Vous n'avez pas les autorisations pour",
        402: "Vous n'avez pas les autorisations pour",
        403: "Vous n'avez pas les autorisations pour",
        404: "La ressource est introuvable, impossible de",
        405: "Vous n'avez pas les autorisations pour",
        500: "Une erreur inattendue est survenue pour",
        //
        create: 'créer',
        read: 'lire',
        update: 'modifier',
        delete: 'supprimer'
    }
};

/**
 * Ext.Array.createRange()
 */
Ext.Array.createArrayStoreRange = function(min, max, step, pad) {
    var step = step || 1,
        pad = pad || 0,
        array = [];
    var condition = function(n, max, step) {
        return (step > 0) ? n <= max : n >= max;
    }
    for (var n=min; condition(n, max, step); n=n+step) {
        // Pads number
        var s = n.toString();
        if (s.length < pad) {
            s = ('0000000000' + s).slice(-pad);
        }
        // Adds number to result array
        array.push(s);
    }
    return array;
};

/**
 * Date ArrayStore data
 */
Ext.ns('Ext.ia.staticdata');
Ext.ia.staticdata.Days = function() {
    var values = Ext.Array.createArrayStoreRange(1, 31, 1, 2),
        data = [[0, '-']];
    Ext.each(values, function(v) {
        data.push([parseInt(v), v]);
    });
    return data;
}();
Ext.ia.staticdata.Months = function() {
    var values = ["Janv", "Févr", "Mars", "Avr", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc"],
        data = [[0, '-']];
    Ext.each(values, function(v, k) {
        data.push([parseInt(k+1), v]);
    });
    return data;
}();
Ext.ia.staticdata.Years = function() {
    var values_all = Ext.Array.createArrayStoreRange(2100, 1900, -1, 4),
        values_sel = Ext.Array.createArrayStoreRange(2020, 1995, -1, 4),
        data = [];
    // Selected years around current year
    data.push([0, '-']);
    Ext.each(values_sel, function(v) {
        data.push([parseInt(v), v]);
    });
    // All years
    data.push([0, '-']);
    Ext.each(values_all, function(v) {
        data.push([parseInt(v), v]);
    });
    return data;
}();

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
    constructor: function(config) {
        this.callParent(arguments);
        // Ensures store.params is a hashtable
        this.params = this.params || {};
    },
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
        // ExtJS 3.0 Store.params simulation
        beforeload: function() { this.applyParamsToProxy() },
        beforesync: function() { this.applyParamsToProxy() },
        beforeprefetch: function() { this.applyParamsToProxy() },
        // Loaded flag (TODO: is it used/necessary ?)
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
    // CRUD notifications
    afterRequest: function(request, success) {
        // Retrieves 'HTTP status' and 'operation.action'
        var status = success ?
                request.operation.response.status :
                request.operation.error.status,
            action = request.operation.action,
            proxy = this;
        // Determines whether to notify or not
        if (success || action == 'read' && status != 404) return;
        // Captions pool as local variables
        var statuses = Ext.ia.caption.status,
            types = Ext.ia.caption.type,
            titles = Ext.ia.caption.titles,
            texts = Ext.ia.caption.texts;
        // Create text to display
        var type = types[status],
            model = proxy.model.prototype.modelName.split('.').pop().replace(/([a-z])([A-Z])/g, "$1 $2").toLowerCase(),
            title = [
                titles[action],
                titles[status]
            ].join(' '),
            message = [
                texts[status],
                texts[action],
                'la ressource',
                '<em>'+model+'</em>'
            ].join(' ');
            iconCls = 'ux-notification-icon-'+type,
            cls = [
                Ext.ia.window.Notification.prototype.cls,
                'ux-notification-bg-'+type
            ].join(' ');
        // Actual notification display
        Ext.create('Ext.ia.window.Notification', {
            title: title,
            html: [
                '<strong>'+message+'</strong>'
            ].join('<br/>'),
            iconCls: iconCls,
            cls: cls,
            autoHide: success,
            hideDuration: 300
        }).show()
    },
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
            record = this.getRecord(gridView, rowIndex, colIndex, item),
            fetch = this.getFetch(gridView, rowIndex, colIndex, item),
            id = (record && record.id) || (fetch && fetch.id);
        // Prevents opening detail on fantom records (eg. id==0)
        if (!id) {
            Ext.Msg.alert(
                'Afficher les détails',
                'Vous devez enregistrer les modifications avant de pouvoir visualiser les détails.'
            );
            return;
        }
        // Creates popup
        var popup = new Ext.ia.window.Popup({
            title: 'Détails',
            item: new this.form({
                frame: false,
                record: record,
                fetch: fetch,
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
        //
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
        // Manages the unchecking of other exising rows checkboxes
        // (visual purpose only, the server-side logic MUST take care of setting all other options to false)
        this.on('checkchange', this.click);
        // Disables clicking when the grid row is in edit mode
        this.on('click', function() { return Boolean(this.editable) });
    },
    click: function(checkcolumn, recordIndex, checked) {
        // Sets all visible radiocolumns to false (unchecked),
        // except the checked radiocolumn
        var store = this.up('grid').store,
            fieldname = this.dataIndex;
        // Sets all loaded records to false, except the clicked record
        Ext.each(store.getRange(), function(record) {
            if (store.getAt(recordIndex) == record) return;
            record.set(fieldname, false);
        });
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
Ext.define('Ext.grid.column.Template', {
    extend:'Ext.grid.Column',
    alias: 'widget.ia-percentcolumn',
    format: '000',
    tpl: '{taux_activite}<tpl if="taux_activite!=null">%</tpl>'
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
        if (store.load && !store.autoLoad && !store.loaded && !store.isLoading()) {
            store.load();
        }
    },
    // Fixes a "bug" on combo columns: when stores loads too late,
    // the valueField value is shown instead of displayField value.
    renderer: function(value, metaData, record, rowIndex, colIndex, store) {
        var column = this.columns[colIndex],
            editor = column.editor || column.field || column.initialConfig.editor || column.initialConfig.field, // column.initialConfig.* is for when store is an ArrayStore
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
    queryMode: 'local', // http://stackoverflow.com/questions/6587238/loading-the-items-for-a-combo-box-in-advance-with-extjs
    initComponent: function() {
        var me = this,
            store = this.store;
        me.callParent();
        // Manages store autoloading & exceptions
        this.on('afterrender', function() {
            if (store && !store.autoLoad && !store.loaded && !store.isLoading()) {
                store.load(function(records, operation, success) {
                    // Masks the component
                    if (!success && operation.action == 'read') {
                        var height = me.getHeight(),
                            combo = me.el.child('.x-form-item-body');
                        combo.addCls('x-ia-panel-mask x-ia-panel-mask-'+Ext.ia.caption.type[operation.error.status]);
                        combo.dom.innerHTML = innerHTML = Ext.ia.caption.status[operation.error.status];
                        combo.setHeight(height);
                    }
                });
            }
        });
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
 * Extends Ext.form.field.Number with
 * - min/max values set to 0/100
 */
Ext.define('Ext.ia.form.field.Percentage', {
    extend:'Ext.form.field.Number',
    alias: 'widget.ia-percentfield',
    maxValue: 100,
    minValue: 0
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
        // Applies store params
        store.params[me.paramName] = value;
        me.hasSearch = true;
        me.triggerEl.item(0).setDisplayed('block');
        me.doComponentLayout();
        // Data refresh or Paging reset (whether applicable)
        var paging = this.up('grid').down('pagingtoolbar');
        if (paging) paging.moveFirst();
        else store.load();
        // Extra event
        this.fireEvent('aftersearch', this);
    }
});

/**
 * Extends Ext.ux.form.SearchField with
 * - events firing: beforesearch, aftersearch and resetsearch
 */
Ext.define('Ext.ia.toolbar.Paging', {
    extend: 'Ext.toolbar.Paging',
    alias: 'widget.ia-pagingtoolbar',
    store: null,
    displayInfo: true,
    displayMsg: 'Eléments {0} à {1} sur {2}',
    emptyMsg: "Aucun élément à afficher",
    items: [],
    listeners: {
        // Wait for render time so that the grid store is created
        // and ready to be bound to the pager
        beforerender: function() { this.bindStore(this.up('gridpanel').store) }
    }
    //plugins: Ext.create('Ext.ux.ProgressBarPager', {})
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
    // NOTE: Disabled because it is managed better in ExtJS >= 4.0.7
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

/**
 * Extends Ext.grid.Panel with
 * - Ext.ia.grid.plugin.RowEditing plugin
 */
Ext.define('Ext.ia.grid.EditBasePanel', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.ia-editbasepanel',
    editingPluginId: null,
    plugins: [],
    editable: true,
    pageSize: 0, // Disabled
    autoSync: false,
    initComponent: function() {
        // Creates Editing plugin (storing its id as grid property)
        this.plugins = [new Ext.ia.grid.plugin.RowEditing({
            pluginId: this.editingPluginId = Ext.id()
        })];
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
                    if (operation.success) me.fireEvent('load');
                }
            });
        }});
        // Manages proxy exceptions:
        // - Masks component
        // - Reverts store data on proxy exception
        this.on({afterrender: function() {
            var me = this;
            this.store.getProxy().on({
                exception: function(proxy, response, operation) {
                    // Masks the component
                    if (operation.action == 'read') {
                        me.addCls('x-ia-panel-mask x-ia-panel-mask-'+Ext.ia.caption.type[operation.error.status]);
                        me.dockedItems.each(function(c) { c.hide() });
                        me.body.hide();
                        me.el.dom.innerHTML = innerHTML = Ext.ia.caption.status[operation.error.status];
                    }
                    // Reverts deleted records
                    if (operation.action == 'destroy') {
                        me.store.removeAll();
                        me.store.removed = [];
                        me.store.load();
                    }
                }
            });
        }});
        // Fixes incorrect grid layout that occurs
        // occasionaly when grid is too narrow (FIXME)
        this.on({load: function() {
            this.doComponentLayout();
        }});
    },
    getEditingPlugin: function() {
        return this.getPlugin(this.editingPluginId);
    },
    setVersion: function(version) {
        // Sets grid store xversion
        this.store.params['xversion'] = version;
        this.store.load();
        // Sets grid columns combo stores xversion
        Ext.each(this.columns, function(column) {
            var editor = column.getEditor(),
                store = (editor) ? editor.store : null,
                proxy = (store) ? store.proxy : null,
                type = (proxy) ? proxy.type : null;
            if (type == 'ia-rest') {
                editor.store.params['xversion'] = version;
                editor.store.load();
            }
        });
        // Sets grid as not editable and updates its state
        // - storing the previous 'editable' state
        if (typeof(this._editableInit)=='undefined') this._editableInit = this.editable;
        // - setting form as not editable if versioned,
        //   or restores stored editable state if not versioned
        this.editable = version ? false : this._editableInit;
        // - updating component state
        this.updateState();
    },
    updateState: function() {
        // Toggles grid columns editability (eg. checkboxes)
        // (restoring initial value afterwards)
        var version = this.store.params['xversion'];
        Ext.each(this.columns, function(c) {
            if (!c.isXType('ia-radiocolumn')) return;
            if (typeof(c._editableInit)=='undefined') c._editableInit = c.editable;
            c.editable = version ? false : c._editableInit;
        });
        // Toggles dockedItems elements (eg. buttons)
        this.dockedItems.each(function(toolbar) {
            toolbar.cascade(function(c) {
                if (c.updateState) c.updateState();
            })
        });
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
    extend: 'Ext.ia.grid.EditBasePanel',
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
    toolbarButtons: ['add', 'delete', 'save', 'search'],
    toolbarLabels: {
        add: 'Ajouter',
        delete: 'Supprimer',
        save: 'Enregistrer les modifications',
        search: 'Rechercher'
    },
    pageSize: 10,
    editable: true,
    dockedItems: [],
    bbar: new Ext.ia.toolbar.Paging(),
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
        // Initializes Component
        var me = this;
        me.callParent();
    },
    makeDockedItems: function() {
        var me = this;
        var add = {
            text: this.toolbarLabels.add,
            iconCls: 'icon-add',
            handler: this.addItem,
            scope: this,
            updateState: function() {
                this.setDisabled(!me.editable);
            }
        };
        var del = {
            text: this.toolbarLabels.delete,
            iconCls: 'icon-delete',
            handler: this.removeItem,
            scope: this,
            // Manages button disable state
            disabled: true,
            listeners: {afterrender: function() {
                var grid = this.up('grid');
                grid.on('selectionchange', this.updateState, this);
            }},
            updateState: function() {
                var records = me.getSelectionModel().getSelection();
                // Disables if no record selected,
                // or if grid is not editable (see this.editable)
                this.setDisabled(!(records.length && me.editable));
            }
        };
        var save = {
            text: this.toolbarLabels.save,
            iconCls: 'icon-save',
            handler: this.syncItems,
            scope: this,
            // Manages button disable state
            disabled: true,
            listeners: {afterrender: function() {
                var store = this.up('grid').getStore();
                store.on('add', this.updateState, this);
                store.on('update', this.updateState, this);
                store.on('remove', this.updateState, this);
            }},
            updateState: function() {
                var store = me.getStore();
                // Disables if no record selected,
                // or if grid is not editable (see this.editable)
                this.setDisabled(!store.getNonPristineRecords().length);
            }
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
            },
            updateState: function() {
                // Search is disabled if store is versioned
                // because server-side does not support
                // searching on versioned datasets yet.
                this.setDisabled(me.store && me.store.params.xversion);
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
 * Selection grid enables the user to add items from one store into another
 * by searching the source store using an autocomplete list
 * and adding items to the destination store.
 * The developer can write the makeData() conversion function,
 * in order to translate source store record into destination store record.
 */
Ext.define('Ext.ia.selectiongrid.Panel', {
    extend: 'Ext.ia.grid.EditBasePanel',
    alias: 'widget.ia-selectiongrid',
    //uses:?
    requires: [
        'Ext.grid.Panel',
        'Ext.form.field.ComboBox'
    ],
    config: {
        combo: {
            store: null,
            pageSize: 5
        },
        grid: {
            store: null,
            autoSync: false,
        },
        makeData: function(record) {
            // Returns a hashtable for feeding Ext.data.Model data, eg:
            // return {
            //     field1: record.get('id'),
            //     field2: record.get('name'),
            //     field3: 'static value'
            // }
            return record.data;
        },
    },
    tbar: [],
    initComponent: function() {
        // Component
        this.store = this.grid.store;
        this.columns = this.grid.columns;
        // Top toolbar
        this.tbar = [
            'Ajouter',
            this.getCombo()
        ].concat(this.tbar);
        // Bottom toolbar
        this.bbar = [{
            text: 'Supprimer la sélection',
            iconCls: 'icon-delete',
            handler: function() {
                var grid = this.up('gridpanel');
                var selection = grid.getView().getSelectionModel().getSelection()[0];
                if (selection) grid.store.remove(selection);
            },
            disabled: true,
            listeners: {afterrender: function() {
                var me = this,
                    grid = this.up('grid');
                grid.on('selectionchange', function(view, records) {
                    me.updateState();
                });
            }},
            updateState: function() {
                var grid = this.up('grid');
                // Disables if no row selected
                // or if grid is not editable (see this.editable)
                var selection = grid.getSelectionModel().getSelection(),
                    count = selection.length;
                this.setDisabled(!(grid.editable && count));
            }
        }];
        // Parent init
        var me = this;
        me.callParent();
    },
    getCombo: function() {
        // Sets pageSize to combo store
        this.combo.store.pageSize = this.combo.pageSize || this.config.combo.pageSize;
        // Creates combo instance
        //return new Ext.ia.form.field.ComboBox({
        return new Ext.form.field.ComboBox({
            store: this.combo.store,
            pageSize: true, // Should equal the store.pageSize, but it works well like that...
            queryParam: 'xquery',
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
                        '  <div>{pays_nom}{[values.pays_nom ? ",":"&nbsp;"]} {pays_code}</div>',
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
            },
            updateState: function() {
                var grid = this.up('grid');
                // Disables if grid is not editable (see this.editable)
                this.setDisabled(!grid.editable);
            }
        });
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
 * "Create version" button widget.
 */
Ext.define('Ext.ia.button.CreateVersion', {
    extend:'Ext.Button',
    alias: 'widget.ia-version-create',
    text:'Créer une version',
    initComponent: function() {
        this.addEvents(
            /**
             * @event createversion
             * Fires after a new version has been successfuly created
             * @param {Object} result The server-side result
             * @param {Number} version The created version id
             */
            'createversion'
        );
        this.callParent();
    },
    handler: function() {
        var me = this;
        this.window = new Ext.window.Window({
            title: 'Créer une version',
            autoShow: true,
            modal: true,
            frame: false,
            resizable: false,
            items: [{
                xtype: 'form',
                border: false,
                bodyPadding: 10,
                items: [{
                    xtype: 'panel',
                    html: "Cette action crée une version qui consiste en un instantané de l'état actuel des données.",
                    border: false,
                    bodyPadding: '10px 0',
                    width: 350
                }, {
                    xtype: 'textfield',
                    allowBlank: false, // FIXME: Messes with form validation
                    fieldLabel: 'Commentaire',
                    labelWidth: 75,
                    width: 350,
                    listeners: {
                        afterrender: function(field) { field.focus(false, 100) }
                    }
                }],
                buttons: [{
                    xtype: 'button',
                    text: 'Créer',
                    formBind: true, //only enabled once the form is valid
                    disabled: true,
                    handler: function() { me.createVersion() }
                }],
                listeners: {
                    // Submit on enter key press
                    afterrender: function(form, options){
                        this.keyNav = Ext.create('Ext.util.KeyNav', this.el, {
                            enter: this.getDockedComponent(0).down('button').handler,
                            scope: this
                        });
                    }
                }
            }]
        });
    },
    getForm: function() {
        return this.up('form');
    },
    createVersion: function() {
        var me = this,
            field = this.window.down('textfield'),
            comment = field.getValue(),
            form = this.getForm(),
            record = form.record,
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
                field.reset();
                me.window.close();
                var result = Ext.JSON.decode(xhr.responseText);
                me.fireEvent('createversion', result, result.xinsertid);
            },
            failure: function(xhr) {
                var r = Ext.JSON.decode(xhr.responseText);
                me.window.close();
                Ext.Msg.show({
                    title: 'Erreur',
                    msg: 'Vous ne pouvez pas créer deux version consécutives<br/>sans avoir effectué de modification intermédiaire.',
                    buttons: Ext.Msg.OK,
                    icon: Ext.Msg.ERROR
                });
            }
        });
    }
});

/**
 * Extends combobox for version selection:
 * this is to be used within a form.
 * Does:
 * - sets xversion parameter to the form' store
 * - sets xversion parameter to the form' grids' stores
 */
Ext.define('Ext.ia.form.field.VersionComboBox', {
    extend:'Ext.ia.form.field.ComboBox',
    alias: 'widget.ia-version-combo',
    typeAhead: false,
    editable: false,
    listConfig: {
        getInnerTpl: function() {
            return [
                '<div>',
                '  <tpl if="version_id==0">Version actuelle</tpl>',
                '  <tpl if="version_id!=0">{version_id}</tpl>',
                '  <tpl if="version_commentaire"> - {version_commentaire}</tpl>',
                '</div>'
            ].join('');
        }
    },
    emptyText: 'Version actuelle',
    valueField: 'version_id',
    displayField: 'version_id',
    store: null,
    modelname: null,
    modelid: null,
    width: 350,
    getTopLevelComponent: function() {
        return this.up('form');
    },
    changeVersion: function(version) {
        var component = this.getTopLevelComponent();
        component.setVersion(version);
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
        this.store = new iafbm.store.VersionRelation({
            params: {
                model_name: this.modelname,
                id_field_value: this.modelid,
                version_operation: 'tag',
                xorder_by: 'version_id',
                xorder: 'DESC'
            }
        });
        // Adds a record for current version
        this.store.on({load: function() {
            this.insert(0, {id: 0, version_id: 0});
        }});
        //
        var me = this;
        me.callParent();
        //
        this.on({
            select: function(combo, records) {
                var record = records.shift(),
                    version = record.get('version_id'),
                    commentaire = record.get('version_commentaire');
                this.changeVersion(version);
                this.fireEvent('changeversion', me, version);
                // Sets templated text in combo
                var display = (version) ?
                    Ext.String.format("Version {0} - {1}", version, commentaire) :
                    "Version actuelle";
                this.setRawValue(display);
            },
            expand: function() {
                // Loads on expand to update versions list.
                // Prevents loading store twice when the store is
                // not yet loaded (eg. on first expand).
                // NOTE: Disabled for the moment because it causes
                //       the combo to collapse abruptly and makes it unusable.
                // NOTE: Instead, ia-versioning widget reloads combo store on
                //       ia-version-create 'createversion' event.
                // if (this.store.loaded) this.store.load();
            }
        });
    }
});

Ext.define('Ext.ia.Versioning', {
    extend:'Ext.container.Container',
    alias: 'widget.ia-versioning',
    layout: 'hbox',
    comboConfig: {
        modelname: null,
        modelid: null,
        getTopLevelComponent: null
    },
    formConfig: {
    },
    initComponent: function() {
        var combo = Ext.apply({xtype: 'ia-version-combo'},this.comboConfig),
            button = Ext.apply({xtype: 'ia-version-create'},this.formConfig),
            checkbox = {
                xtype: 'checkbox',
                boxLabel: 'Afficher toutes les versions',
                handler: function(checkbox, value) {
                    // Removes filter on 'version_operation' through store.params
                    var store = this.up('ia-versioning').down('ia-version-combo').store;
                    if (value) {
                        this._version_operation = store.params.version_operation;
                        delete(store.params.version_operation);
                    } else {
                        store.params.version_operation = this._version_operation;
                    }
                }
        };
        // Adds widgets
        this.items = [combo, button/*, checkbox*/];
        this.callParent();
        // Reloads combo store on createversion
        var buttonInstance = this.items.getAt(1),
            comboInstance = this.items.getAt(0);
        buttonInstance.on('afterrender', function() {
            this.on('createversion', function(result, version) {
                comboInstance.store.load();
            });
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
    editable: true,
    record: null,
    fetch: {
        model: null,
        id: null,
        params: {},
        xversion: null
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
                success: function(record, operation) {
                    // FIXME: Test on xversion prevents displaying an error
                    //        meanwhile the actual versioned record is loaded (see this.initComponent()).
                    //        This is dirty
                    if (!record && me.fetch.xversion) return;
                    // Checks record before applying data
                    if (!record) {
                        proxy.fireEvent('exception', proxy, operation.response, operation);
                        return;
                    }
                    // Load record into form
                    me.record = record;
                    me.getForm().loadRecord(me.record);
                    me.fireEvent('load', me, me.record);
                },
                // Masks the component if data loading fails
                failure: function(record, operation) {
                    var height = me.getHeight();
                    // Hides existing items
                    var toolbar = me.dockedItems.getAt(me.dockedItems.findIndex('xtype', 'toolbar'));
                    toolbar.hide();
                    me.items.each(function(c) { c.hide() });
                    // Sets message class
                    me.body.addCls('x-ia-panel-mask x-ia-panel-mask-'+Ext.ia.caption.type[operation.error.status]);
                    // Creates message panel item
                    me.add({
                        html: Ext.ia.caption.status[operation.error.status],
                        border: false,
                        frame: false,
                        bodyStyle: 'background-color:transparent'
                    });
                    me.setHeight(height);
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
            record = this.getRecord(),
            form = this.getForm();
        //TODO: would it be clever to reuse the record validation be used here?
        if (!form.isValid()) return;
        // Updates record from form values
        // FIXME: updateRecord() will trigger the save action
        //        if the record belongs to Store with autoSync,
        //        which will trigger the POST request twice :(
        form.updateRecord(record);
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
            //FIXME: For debugging issue 'unsaved changes dialog shows when not applicable'
            //console.log('Field:', f.name, 'record:', record.get(f.name), 'field:', f.getValue());
            // Skips ia-version-combo fields
            if (f.isXType('ia-version-combo')) return;
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
    setVersion: function(version) {
        var me = this;
        // Sets form record xversion
        this.loadRecord({xversion:version});
        // Cascade setVersion() where applicable
        this.cascade(function(c) {
            if (c === me) return;
            if (c.setVersion) c.setVersion(version);
        });
        // Sets grid as not editable and updates its state
        // - storing the previous 'editable' state
        if (typeof(this._editableInit)=='undefined') this._editableInit = this.editable;
        // - setting form as not editable if versioned,
        //   or restores stored editable state if not versioned
        this.editable = version ? false : this._editableInit;
        // - updating component state
        this.updateState();
    },
    updateState: function() {
        var me = this;
        // Toggles form items (eg. fields)
        // FIXME: Form items disablement logic could be moved
        //        inside a Field-specific updateState() method
        this.cascade(function(c) {
            // Do not disable version-combo
            if (c.isXType('ia-version-combo')) return;
            // Toggles form fields to 'readonly' mode
            if (c.isXType('field')) c.setReadOnly(!me.editable);
            // Toggles contained buttons
            if (c.isXType('button')) c.setDisabled(!me.editable);
        });
        // Cascades call to updateState()
        // on form items
        this.cascade(function(c) {
            // Skips processing itself
            if (c === me) return;
            if (c.updateState) c.updateState();
        });
        // Cascades call to updateState()
        // on dockedItems elements (eg. buttons)
        this.dockedItems.each(function(dockedItem) {
            dockedItem.items.each(function(c) {
                if (c.updateState) c.updateState();
            });
        });
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
        // Locks form if not editable
        if (!this.editable) this.lockFields(this.editable);
        // If applicable, create a save button for the form
        if (this.record || this.fetch.model) {
            var me = this;
            if (!this.tbar) this.tbar = [];
            this.tbar.unshift({
                xtype: 'button',
                text: 'Enregistrer',
                iconCls: 'icon-save',
                scale: 'medium',
                handler: function() { me.saveRecord() },
                updateState: function() {
                    this.setDisabled(!me.editable);
                }
            });
        }
        //
        var me = this;
        me.callParent();
        // Manages version combo value if a versioned record is requested
        // FIXME: This should be implemented in ia-version-combo (Form should not be aware of version-combo)
        if (this.fetch && this.fetch.xversion) {
            var version = this.fetch.xversion,
                combo = this.down('ia-version-combo');
            // Applies version if ia-version-combo exists
            if (combo && version) {
                // Disables remote sorting, versions are sorted locally
                combo.store.remoteSort = false;
                combo.store.sort('version_id', 'DESC');
                // Adds requested xversion to versions list
                // if not already existing
                combo.store.on('load', function() {
                    if (combo.store.findExact('version_id', version) < 0) {
                        this.insert(0, {
                            version_id: version,
                            version_commentaire: '***'
                        });
                    }
                });
                // Selects requested version,
                // updating form stores through ia-version-combo wiget.
                combo.store.load(function(records, operation, success) {
                    var record = this.getAt(this.findExact('version_id', version));
                    combo.fireEvent('select', combo, [record]);
                });
            }
        }
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
    setVersion: function(version) {
        this.items.each(function(c) {
            // We need to go down one nesting level
            // to access the actual tab content
            c.down().setVersion(version);
        });
    },
    lockFields: function(lock) {
        this.items.each(function(c) {
            // We need to go down one nesting level
            // to access the actual tab content
            c.down().lockFields(lock);
        });
    },
    initComponent: function() {
        // 'Closed commission' information message display
        this.tbar = {
            xtype: 'panel',
            html: '<b>Cette commission est clôturée et ne peut être modifiée</b>',
            bodyStyle: {
                padding: '15px',
                background: '#dfd',
                color: '#030',
                'text-align': 'center'
            },
            // Display logic
            hidden: true,
            listeners: {added: function() {
                var me = this,
                    form_apercu = this.up('tabpanel').items.getAt(0).down('form');
                form_apercu.on('load', function() {
                    if (this.getRecord().get('commission_etat_id') == 3) {
                        me.show();
                    }
                });
            }}
        }
        // Component initialisation
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

/**
 * This form is used for the Commission details:
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
        var me = this;
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
            },
            updateState: function() {
                this.setDisabled(!me.editable);
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


/**
 * Extends Ext.ux.window.Notification with
 * - default options
 */
Ext.define('Ext.ia.window.Notification', {
    extend: 'Ext.ux.window.Notification',
    alias: 'widget.ia-notification',
    cls: 'ux-notification-light',
    iconCls: 'ux-notification-icon-information',
    width: 300,
    slideInDuration: 66,
    slideBackAnimation: 'easeOut',
    slideBackDuration: 66,
    hideDuration: 3000,
    autoHideDelay: 500,
    position: 't',
    closable: true,
    shadow: true,
    useXAxis: true,
    //title: '',
    //html: ''
})

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