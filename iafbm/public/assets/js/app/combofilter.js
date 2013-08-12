/**
 * Ensemble de 4 combobox pour filtrer un grille par :
 * - Service
 * - Catégorie professionnelle
 * - Sous catégorie professionnelle
 *
 * ainsi que le choix de l'unité par défaut
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
            id: 'service',
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
                    Ext.getCmp('service').reset();
                }
            },{
                xtype: 'combo',
                id: 'catprof',
                padding: '10',
                margin: '0 7 0 0',
                store: new iafbm.store.ActiviteNom(),
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
                            // la panel et le filtre
                            var pnl = this.up('form');
                            var f;
                            // recharger les données de la liste des sous catégories
                            var cb = Ext.getCmp('souscatprof');
                            cb.store.clearFilter();
                            // reset le combo souscat sans fire l'event
                            cb.suspendEvents();
                            cb.reset();
                            cb.resumeEvents();
                            if(newValue !== null) {
                                // former le filtre sans les sous-catégories
                                f = pnl.parseFilterData(false);
                                // appliquer le filtre aux sous-catégories
                                cb.store.filter([{
                                    property: 'categorie_professionnelle_id',
                                    value: newValue
                                }]);
                            }
                            else {
                                // dans le cas où on reset les combobox
                                // former le filtre avec les sous-catégories
                                f = pnl.parseFilterData();
                            }
                            // recharger la liste des données avec le nouveau filtre
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
                    Ext.getCmp('catprof').reset();
                }
            },{
                xtype: 'combo',
                id: 'souscatprof',
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
                    Ext.getCmp('souscatprof').reset();
                }
            }
        ]}
    ],
    parseFilterData: function(resetFlag) {
        /**
        * Création du filtre de recherche.
        * On récupère les données des combobox, et on crée l'objet en fonction.
        * Si les données sont null, on ne les ajoute pas au filtre.
        */
        var resetFlag = resetFlag || true;
        // récupérer les données des combobox
        var valServ   = Ext.getCmp('service').getValue();
        var valCat    = Ext.getCmp('catprof').getValue();
        var valSubCat = Ext.getCmp('souscatprof').getValue();
        // créer le filtre que si les valeurs ne sont pas null
        var result = {};
        if(valServ !== null)
            result['uo_id'] = valServ;
        if(valCat !== null)
            result['contrat_categorie_professionnelle_id'] = valCat;
        if(valSubCat !== null && resetFlag)
            result['contrat_sous_categorie_professionnelle_id'] = valSubCat;
        return result;
    },
    applyFilterData: function(filterData, store) {
        delete store.params['uo_id'];
        delete store.params['contrat_categorie_professionnelle_id'];
        delete store.params['contrat_sous_categorie_professionnelle_id'];
        Ext.apply(store.params, filterData);
        store.load();
    },
    fillUnitFields: function(column, value) {
        /**
        * Remplir les champs avec les données clonées de la personne active.
        * si overwrite est true, les champs déjà remplis sont écrasés.
        */
        var store = Ext.getCmp(this.gridId).store;
        // remplir les champs des autres lignes avec l'unité choisie
        for(cnt=0; cnt<store.getCount(); cnt++) {
            store.getAt(cnt).set(column, value);
        }
        // sauvegarder automatiquement les données
        store.sync();
    }
});
