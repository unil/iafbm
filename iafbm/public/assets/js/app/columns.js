// Columns
Ext.ns('iafbm.columns');
iafbm.columns.Personne = [{
    xtype: 'ia-actioncolumn-detailform',
    form: iafbm.form.Personne
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
    header: "Pays d'origine",
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
    header: "Carrière professionnelle",
    flex: 1,
    sortable: false,
    dataIndex: '_activites'
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
    xtype: 'ia-actioncolumn-detailform',
    form: iafbm.form.Personne,
    getRecord: function(gridView, rowIndex, colIndex, item) {
        return null;
    },
    getFetch: function(gridView, rowIndex, colIndex, item) {
        var commission_membre = gridView.getStore().getAt(rowIndex),
            personne_id = commission_membre.get('personne_id'),
            version = commission_membre.get('version_id');
        // Load versioned record
        return {
            model: iafbm.model.Personne,
            id: personne_id,
            xversion: version
        };
    }
}, {
    // NOTE: This column implements *very* lazy data loading.
    header: "Activité",
    dataIndex: 'activite_id',
    xtype: 'templatecolumn',
    tpl: '{activite_nom_abreviation}',
    width: 150,
    editor: {
        xtype: 'ia-combo',
        editable: false,
        typeAhead: false,
        store: new iafbm.store.PersonneActivite({
            autoLoad: false,
            params: {
                personne_id: null,
                xversion: null,
                xjoin: 'activite,activite_nom'
            }
        }),
        valueField: 'activite_id',
        displayField: 'activite_nom_abreviation',
        listeners: {
            // Manages list filtering: only shows 'acitivites' related to the 'personne'
            beforequery: function(queryEvent, eventOpts) {
                var store = this.getStore(),
                    record = this.up('form').getRecord(),
                    personne_id = record.get('personne_id'),
                    version_id = record.get('version_id');
                // Assigns store personne_id param if not already set
                if (
                    store.params.personne_id == personne_id &&
                    store.params.xversion == version_id
                ) return;
                store.params.personne_id = personne_id;
                store.params.xversion = version_id;
                store.load(function(records, operation, success) {
                    if (!success) return;
                    this.insert(records.length, {
                        id: null,
                        activite_id: null,
                        activite_nom_abreviation: '(Aucune)'
                    });
                });
            },
            afterrender: function() {
                // FIXME: not fired by the ext framework :(
                // Displays activite_abreviation_nom as combo raw value
                // because the store only loads on collapse
                var activite_abreviation_nom = this.up('form').getRecord().get('activite_nom_abreviation');
                var me = this,
                    setRawValue = function() {
                        if (!me.getRawValue()) me.setRawValue(activite_abreviation_nom);
                    };
                Ext.defer(setRawValue, 500);
            },
            select: function(combo, selectedRecords) {
                // Affects the selected record 'activite_nom_abreviation' to grid record
                // in order to display the value once the combo collapses
                var activite_abreviation_nom = selectedRecords.pop().get('activite_nom_abreviation');
                this.up('form').getRecord().set('activite_nom_abreviation', activite_abreviation_nom);
            }
        }
    }
}, {
    header: "Nom",
    dataIndex: 'personne_nom',
    flex: 1
}, {
    header: "Prénom",
    dataIndex: 'personne_prenom',
    flex: 1
}, {
    // NOTE: This column implements *very* lazy data loading.
    header: "Dépt / Service",
    dataIndex: 'rattachement_id',
    xtype: 'templatecolumn',
    tpl: '{rattachement_nom}',
    flex: 1,
    editor: {
        xtype: 'ia-combo',
        valueField: 'rattachement_id',
        displayField: 'rattachement_nom',
        store: new iafbm.store.PersonneActivite({
            autoLoad: false,
            params: {
                personne_id: null,
                order_by: 'rattachement_nom', // FIXME: this is not working (because it's a foreign key)
            }
        }),
        listeners: {
            // Manages list filtering: only shows 'acitivites' related to the 'personne'
            beforequery: function(queryEvent, eventOpts) {
                var store = this.getStore(),
                    record = this.up('form').getRecord(),
                    personne_id = record.get('personne_id'),
                    version_id = record.get('version_id');
                // Assigns store personne_id param if not already set
                if (
                    store.params.personne_id == personne_id &&
                    store.params.xversion == version_id
                ) return;
                store.params.personne_id = personne_id;
                store.params.xversion = version_id;
                store.load(function(records, operation, success) {
                    if (!success) return;
                    this.insert(records.length, {
                        id: null,
                        activite_id: null,
                        rattachement_nom: '(Aucune)'
                    });
                });
            },
            afterrender: function() {
                // FIXME: not fired by the ext framework :(
                // Displays activite_abreviation_nom as combo raw value
                // because the store only loads on collapse
                var rattachement_nom = this.up('form').getRecord().get('rattachement_nom');
                var me = this,
                    setRawValue = function() {
                        if (!me.getRawValue()) me.setRawValue(rattachement_nom);
                    };
                Ext.defer(setRawValue, 500);
            },
            select: function(combo, selectedRecords) {
                // Affects the selected record 'activite_nom_abreviation' to grid record
                // in order to display the value once the combo collapses
                var rattachement_nom = selectedRecords.pop().get('rattachement_nom');
                this.up('form').getRecord().set('rattachement_nom', rattachement_nom);
            }
        }
    }
}, {
    header: "Fonction",
    dataIndex: 'commission_fonction_id',
    width: 250,
    xtype: 'ia-combocolumn',
    editor: {
        xtype: 'ia-combo',
        displayField: 'nom',
        valueField: 'id',
        allowBlank: false,
        store: new iafbm.store.CommissionFonction()
    }
}, {
    header: null,
    dataIndex: '_uptodate',
    sortable: false,
    width: 20,
    xtype: 'actioncolumn',
    items: [{
        icon: x.context.baseuri+'/a/img/ext/arrow_refresh.png',
        text: 'Actualiser',
        tooltip: 'Actualiser',
        handler: function(grid, rowIndex, colIndex) {
            // Prevents update if CommissionMembre store is versioned
            var store = grid.getStore();
            if (store.params && store.params.xversion) {
                Ext.Msg.alert(
                    'Actualisation du membre', [
                        'Vous ne pouvez pas actualiser le membre',
                        'lorsque vous visualisez une commission versionée.',
                        '<br/><br/>',
                        'Affichez d\'abord la version actuelle de la commission.'
                    ].join(' ')
                );
                return;
            }
            // Confirms and updates CommissionMembre
            var msg = [
                'Cette opération met à jour les données du membre',
                'à partir de la dernière version de la personne y relative.',
                '<br/><br/>',
                'Voulez-vous continuer?'
            ].join(' ');
            Ext.Msg.confirm(
                'Mise à jour du membre',
                msg,
                function(is) {
                    if (is=='yes') this.update_version(grid, rowIndex, colIndex);
                },
                this
            );
        },
        getClass: function(value, metadata, record, rowIndex, colIndex, store) {
            return record.get('_uptodate') ? 'x-hide-display' : '';
        }
    }],
    update_version: function(grid, rowIndex, colIndex) {
        var store = grid.getStore(),
            record = store.getAt(rowIndex);
        record.set('version_id', null);
        store.sync();
    }
}];

iafbm.columns.Candidat = [{
    xtype: 'ia-actioncolumn-detailform',
    form: iafbm.form.Candidat,
    getRecord: function(gridView, rowIndex, colIndex, item) {
        return null
    },
    getFetch: function(gridView, rowIndex, colIndex, item) {
        var candidat = gridView.getStore().getAt(rowIndex);
        return {
            model: iafbm.model.Candidat,
            id: candidat.get('id')
        };
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
        displayField: 'nom',
        valueField: 'id',
        store: new iafbm.store.Genre()
    }
}];

iafbm.columns.Commission = [{
    xtype: 'ia-actioncolumn-redirect',
    width: 25,
    text: 'Détails commission',
    tooltip: 'Détails commission',
    getLocation: function(grid, record, id) {
        return x.context.baseuri+'/commissions/'+id;
    }
}, {
    header: "Type",
    dataIndex: 'commission_type_id',
    width: 100,
    xtype: 'ia-combocolumn',
    field: {
        xtype: 'ia-combo',
        displayField: 'racine',
        valueField: 'id',
        allowBlank: false,
        store: new iafbm.store.CommissionType()
    }
}, {
    header: "N°",
    dataIndex: 'id',
    width: 50
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
    width: 60,
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
    sortable: false,
    width: 150,
}, {
    header: "Etat",
    dataIndex: 'commission_etat_id',
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
    header: "Abréviation",
    dataIndex: 'racine',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
}];

iafbm.columns.Activite = [{
    header: "Nom",
    dataIndex: 'activite_nom_id',
    flex: 1,
    xtype: 'ia-combocolumn',
    field: {
        xtype: 'ia-combo',
        displayField: 'nom',
        valueField: 'id',
        allowBlank: false,
        store: new iafbm.store.ActiviteNom()
    }
}, {
    header: "Section",
    dataIndex: 'section_id',
    flex: 1,
    xtype: 'ia-combocolumn',
    field: {
        xtype: 'ia-combo',
        displayField: 'code',
        valueField: 'id',
        allowBlank: false,
        store: new iafbm.store.Section()
    }
}, {
    header: "Type",
    dataIndex: 'activite_type_id',
    flex: 1,
    xtype: 'ia-combocolumn',
    field: {
        xtype: 'ia-combo',
        displayField: 'nom',
        valueField: 'id',
        allowBlank: false,
        store: new iafbm.store.ActiviteType()
    }
}];

iafbm.columns.Rattachement = [{
    header: "Nom",
    dataIndex: 'nom',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
}];