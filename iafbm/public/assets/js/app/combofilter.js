/**
 * Component to filter a grid
 * To implement see views/common/extjs/grid.tpl
 */
Ext.define('Ext.ia.Combofilter', {
    extend: 'Ext.form.Panel',
    alias: 'widget.ia-combofilter',
    id: "toto",
    // grille à filtrer
    gridId: null,
    items: [],
    filters: [],    
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
                    labelWidth: 150,
                    width: 150,
                    editable: false,
                    displayField: item.displayField,
                    valueField: item.valueField,
                    // événement quand on séléctionne une valeur dans le combobox
                    listeners: {
                        change: {
                            fn: function(obj, newValue, oldValue, eOpts) {
                                // la panel
                                var pnl = this.up('form');
                                // former le filtre
                                var f = pnl.parseFilterData();
                                // recharger les données avec le nouveau filtre
                                var store = Ext.getCmp(pnl.gridId).store;
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
        * Création du filtre de recherche.
        * On récupère les données des combobox, et on crée l'objet en fonction.
        * Si les données sont null, on ne les ajoute pas au filtre.
        */        
        var result = {};
        
        Ext.Array.each(this.filters.items, function(item) {
            var itemValue = Ext.getCmp(item.itemId).getValue();
            if(itemValue !== null) result[item.filterColumn] = itemValue;
        });
        return result;
        
    },
    applyFilterData: function(filterData, store) {
        Ext.Array.each(this.filters.items, function(item) {
            delete store.params[item.filterColumn];
        });
        Ext.apply(store.params, filterData);
        store.load();
    }
});