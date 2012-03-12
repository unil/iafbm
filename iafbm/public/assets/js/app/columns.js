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
        var commission_membre = gridView.getStore().getAt(rowIndex);
        return {
            model: iafbm.model.Personne,
            id: commission_membre.get('personne_id')
        };
    }
}, {
    header: "Activité",
    dataIndex: 'activite_id',
    width: 150,
    xtype: 'ia-combocolumn',
    editor: {
        xtype: 'combo',
        editable: false,
        typeAhead: false,
        // Also retrieves deleted activites for versionned commission_membres
        // FIXME: this can be huge, personne_id filter
        //        containing grid personne_ids should be added
        store: new iafbm.store.PersonneActivite({
            params: {
                'actif[]': 0,
                'actif[]': 1
            }
        }),
        valueField: 'activite_id',
        displayField: 'activite_nom_abreviation',
        // Manages list filtering: only shows 'acitivites' related to the 'personne'
        listeners: {
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
                store.params = {
                    personne_id: personne_id,
                    xversion: version_id
                };
                store.load();
            },
            collapse: function(combo, record, index) {
                var store = this.getStore();
                // Deletes query params
                delete(store.params.personne_id);
                delete(store.params.xversion);
                // Restores actif = [0,1]
                store.params['actif[]'] = 0;
                store.params['actif[]'] = 1;
                // Reloads store
                store.load();
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
    header: "Dépt / Service",
    dataIndex: 'rattachement_id',
    flex: 1,
    xtype: 'ia-combocolumn',
    editor: {
        xtype: 'ia-combo',
        valueField: 'rattachement_id',
        displayField: 'rattachement_nom',
        store: new iafbm.store.PersonneActivite({
            params: {
                order_by: 'rattachement_nom', // FIXME: this is not working (because it's a foreign key)
                //TODO: DISTINCT causes problems with xversion
                //xreturn: 'DISTINCT(rattachement_id), rattachements.nom AS rattachement_nom'
            }
        }),
        // Manages list filtering: only shows 'rattachements' related to the 'personne'
        listeners: {
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
                delete(store.params.actif);
                store.load();
            },
            collapse: function(combo, record, index) {
                var store = this.getStore();
                // Deletes query params
                delete(store.params.personne_id);
                delete(store.params.xversion);
                // Restores actif = [0,1]
                store.params['actif[]'] = 0;
                store.params['actif[]'] = 1;
                // Reloads store
                store.load();
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
    width: 20,
    xtype: 'actioncolumn',
    items: [{
        icon: x.context.baseuri+'/a/img/ext/arrow_refresh.png',
        text: 'Actualiser',
        tooltip: 'Actualiser',
        handler: function(grid, rowIndex, colIndex) {
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