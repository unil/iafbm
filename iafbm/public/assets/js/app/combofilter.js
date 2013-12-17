/**
 * Component to filter a grid
 * To implement see views/common/extjs/grid.tpl
 */
Ext.define('Ext.ia.Combofilter', {
    extend: 'Ext.form.Panel',
    alias: 'widget.ia-combofilter',
    id: "comboFilter",
    // grid to filter
    gridId: null,
    items: [],
    filters: [], //filters from php
    extFilters: [], //filters in functions
    initComponent: function() {
        this.items.items = this.makeItems();
        this.callParent();
    },
    bodyPadding: 0,
    items: {
        xtype: 'fieldcontainer',
        layout: 'hbox',
        margin: 0,
        items: null
    },
    makeItems: function() {
        ret = new Array();
        
        Ext.Array.each(this.filters.items, function(item) {
            /*
             * Default comboBox
             */
            ret.push( 
                {
                    xtype: 'combo',
                    id: item.itemId,
                    padding: '10',
                    margin: '0 7 0 0',
                    store: eval(item.store),
                    fieldLabel: item.fieldLabel,
                    labelSeparator: ': ',
                    labelAlign: 'top',
                    labelWidth: 140,
                    width: 140,
                    editable: false,
                    displayField: item.displayField,
                    valueField: item.valueField,
                    // event on valueSelected in combobox
                    listeners: {
                        change: {
                            fn: function(obj, newValue, oldValue, eOpts) {
                                pnl = this.up('form'), // le panel
                                    store = Ext.getCmp(pnl.gridId).store;
                                    
                                // create filter
                                var f = pnl.parseFilterData();
                                // reload data with the filter
                                pnl.applyFilterData(f, store);
                            }
                        }
                    }
                },{
                    xtype: 'button',
                    text: '&#215;',
                    fieldLabel: 'aa',
                    labelAlign: 'top',
                    margin: '14 5 0 0',
                    handler: function() {
                        Ext.getCmp(item.itemId).reset();
                    }
                }
            );
        });
        
        return ret;
    },
    parseFilterData: function(resetFlag) {
        /**
        * Creation of the search filter.
        * Get data from combobox and create the object filter.
        * if datas are null, no filter.
        */
        var result = new Array();
        
        Ext.Array.each(this.filters.items, function(item) {
            var itemValue = Ext.getCmp(item.itemId).getValue();
            var f;
            
            if(itemValue !== null){
                if(item.specialFilter){
                    f = Ext.create('Ext.util.Filter', {id: item.itemId, filterFn: eval(item.specialFilter), root: 'data'});
                    result.push(f);
                }else{
                    f = Ext.create('Ext.util.Filter', {id: item.itemId, property: item.filterColumn, value: itemValue, exactMatch: true, caseSensitive: false, root: 'data'});
                    result.push(f);
                }
            }
        });
        return result;
    },
    applyFilterData: function(filterData, store) {
        store.clearFilter();
        store.filter(filterData);
    }
});