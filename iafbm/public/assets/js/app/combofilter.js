/**
 * Ensemble de 3 combobox pour filtrer un grille par :
 * - Type d'évaluation
 * - Titre académique
 * - Section
 */
Ext.define('Ext.ia.Combofilter', {
    extend: 'Ext.form.Panel',
    alias: 'widget.ia-combofilter',
    initComponent: function() {
        this.callParent();
    },
    bodyPadding: 0,
    // grille à filtrer
    gridId: null,
    items: [{
        xtype: 'fieldcontainer',
        layout: 'hbox',
        margin: 0,
        items: [{
            xtype: 'combo',
            id: 'type',
            padding: '10',
            margin: '0 7 0 0',
            store: new iafbm.store.EvaluationType(),
            fieldLabel: 'Type',
            labelSeparator: ': ',
            labelAlign: 'top',
            labelWidth: 100,
            width: 100,
            editable: false,
            displayField: 'type',
            valueField: 'id',
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
            // bouton pour remettre à zéro le combo
            xtype: 'button',
            text: '&#215;',
            fieldLabel: 'aa',
            labelAlign: 'top',
            margin: '14 5 0 0',
            handler: function() {
                Ext.getCmp('type').reset();
            }
        },{
            xtype: 'combo',
            id: 'titre',
            padding: '10',
            margin: '0 7 0 0',
            store: new iafbm.store.ActiviteNom({
                params: {
                    'id[]': [1,2,4,5,11,14,15,16,17,22],
                }
            }),
            fieldLabel: 'Titre académique',
            labelSeparator: ': ',
            labelAlign: 'top',
            labelWidth: 200,
            width: 200,
            editable: false,
            displayField: 'abreviation',
            valueField: 'id',
            // événement quand on séléctionne une valeur dans le combobox
            listeners: {
                change: {
                    fn: function(obj, newValue, oldValue, eOpts) {
                        // la panel
                        var pnl = this.up('form');
                        // former le filtre
                        var f = pnl.parseFilterData();
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
                Ext.getCmp('titre').reset();
            }
        },{
            xtype: 'combo',
            id: 'section',
            padding: '10',
            margin: '0 7 0 0',
            store: new iafbm.store.Section(),
            fieldLabel: 'Section',
            labelSeparator: ': ',
            labelAlign: 'top',
            labelWidth: 100,
            width: 100,
            editable: false,
            displayField: 'code',
            valueField: 'id',
            // événement quand on séléctionne une valeur dans le combobox
            listeners: {
                change: {
                    fn: function(obj, newValue, oldValue, eOpts) {
                        // la panel
                        var pnl = this.up('form');
                        // former le filtre
                        var f = pnl.parseFilterData();
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
                Ext.getCmp('section').reset();
            }
        }]}
    ],
    parseFilterData: function(resetFlag) {
        /**
        * Création du filtre de recherche.
        * On récupère les données des combobox, et on crée l'objet en fonction.
        * Si les données sont null, on ne les ajoute pas au filtre.
        */
        var resetFlag = resetFlag || true;
        // récupérer les données des combobox
        var valServ   = Ext.getCmp('type').getValue();
        var valCat    = Ext.getCmp('titre').getValue();
        var valSubCat = Ext.getCmp('section').getValue();
        // créer le filtre que si les valeurs ne sont pas null
        var result = {};
        if(valServ !== null)
            result['evaluation_type_id'] = valServ;
        if(valCat !== null)
            result['activite_nom_id'] = valCat;
        if(valSubCat !== null && resetFlag)
            result['section_id'] = valSubCat;
        return result;
    },
    applyFilterData: function(filterData, store) {
        delete store.params['evaluation_type_id'];
        delete store.params['activite_nom_id'];
        delete store.params['section_id'];
        Ext.apply(store.params, filterData);
        store.load();
    }
});
