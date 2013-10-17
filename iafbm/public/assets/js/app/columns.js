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
        // Loads versioned record (if applicable, eg. xversion > 0)
        return {
            model: iafbm.model.Personne,
            id: personne_id,
            xversion: version
        };
    }
}, {
    header: "Dénomination",
    dataIndex: 'personne_denomination_id',
    xtype: 'ia-combocolumn',
    editor: {
        xtype: 'ia-combo',
        editable: false,
        typeAhead: false,
        store: new iafbm.store.PersonneDenomination(),
        valueField: 'id',
        displayField: 'nom'
    }
}, /* Disabled as of ticket #177 {
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
},*/ {
    header: "Prénom",
    dataIndex: 'personne_prenom',
    flex: 1
}, {
    header: "Nom",
    dataIndex: 'personne_nom',
    flex: 1
}, /* Disabled as of ticket #177 {
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
},*/ {
    header: "Fonction",
    dataIndex: 'commission_fonction_id',
    width: 200,
    xtype: 'ia-combocolumn',
    editor: {
        xtype: 'ia-combo',
        displayField: 'nom',
        valueField: 'id',
        allowBlank: false,
        store: new iafbm.store.CommissionFonction()
    }
}, {
    header: "Complément de fonction",
    dataIndex: 'fonction_complement',
    flex: 1,
    editor: {
        xtype: 'textfield'
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
iafbm.columns.CommissionMembreNonominatif = [{
    header: "Dénomination",
    dataIndex: 'personne_denomination_id',
    xtype: 'ia-combocolumn',
    editor: {
        xtype: 'ia-combo',
        editable: false,
        typeAhead: false,
        store: new iafbm.store.PersonneDenomination(),
        valueField: 'id',
        displayField: 'nom'
    }
}, {
    header: "Prénom et nom",
    dataIndex: 'nom_prenom',
    width: 300,
    editor: {
        xtype: 'textfield'
    }
}, {
    header: "Fonction",
    dataIndex: 'commission_fonction_id',
    width: 200,
    xtype: 'ia-combocolumn',
    editor: {
        xtype: 'ia-combo',
        displayField: 'nom',
        valueField: 'id',
        allowBlank: false,
        store: new iafbm.store.CommissionFonction()
    }
}, {
    header: "Complément de fonction",
    dataIndex: 'fonction_complement',
    flex: 1,
    editor: {
        xtype: 'textfield'
    }
}];

iafbm.columns.Candidat = [{
    xtype: 'ia-actioncolumn-detailform',
    form: iafbm.form.Candidat,
    getRecord: function(gridView, rowIndex, colIndex, item) {
        return null
    },
    getFetch: function(gridView, rowIndex, colIndex, item) {
        var store = gridView.getStore(),
            candidat = store.getAt(rowIndex),
            version = store.params.xversion;
        // Loads versioned record (if applicable, eg. xversion > 0)
        return {
            model: iafbm.model.Candidat,
            id: candidat.get('id'),
            xversion: version
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

// TODO versions
iafbm.columns.Evaluateur = [{
    xtype: 'ia-actioncolumn-detailform',
    form: iafbm.form.Personne,
    getRecord: function(gridView, rowIndex, colIndex, item) {
        return null;
    },
    getFetch: function(gridView, rowIndex, colIndex, item) {
        var commission_membre = gridView.getStore().getAt(rowIndex),
            personne_id = commission_membre.get('personne_id');
            //version = commission_membre.get('version_id');
        // Loads versioned record (if applicable, eg. xversion > 0)
        return {
            model: iafbm.model.Personne,
            id: personne_id,
            //xversion: version
        };
    }
}, {
    header: "Nom",
    dataIndex: 'personne_nom',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
}, {
    header: "Prénom",
    dataIndex: 'personne_prenom',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
},{
    header: "Date de naissance",
    dataIndex: 'personne_date_naissance',
    flex: 1,
    xtype: 'ia-datecolumn',
    field: {
        xtype: 'ia-datefield'
    }
}];

iafbm.columns.Evaluation = [{
    text: 'Informations personnelles',
    columns: [{
        text: '',
        xtype: 'ia-actioncolumn-redirect',
        width: 25,
        tooltip: 'Détails évaluation',
        getLocation: function(grid, record, id) {
            return [
                x.context.baseuri,
                'evaluations',
                record.get('id')
            ].join('/');
        } 
    },/*{
        text: "Nom",
        sortable : true,
        dataIndex: 'personne_id',
        width: 170,
        xtype: 'ia-combocolumn',
        renderer: function(value, metaData, record, rowIndex, colIndex, store) {
            return record.data._prenom_nom;
        },
        editor: {
            xtype: 'ia-combo',
            store: new iafbm.store.PersonneActivite({
                params: {
                    //to avoid having multiples same personnes
                    //xgroup_by: 'personne_id'
                }
            }),
            valueField: 'personne_id',
            displayField: '_nomPrenom',
            allowBlank: false,
            listConfig: {
                loadingText: 'Recherche...',
                emptyText: 'Aucun résultat.',
                // Custom rendering template for each item
                getInnerTpl: function() {
                    var img = x.context.baseuri+'/a/img/icons/trombi_empty.png';
                    return [
                        '<div>',
                        '  <img src="'+img+'" style="float:left;height:39px;margin-right:5px"/>',
                        '  <h3>{personne_prenom} {personne_nom}</h3>',
                        '  <div>{activite_nom_abreviation} {section_code}</div>',
                        '  <div>{[values.personne_date_naissance ? Ext.Date.format(values.personne_date_naissance, "j M Y") : "&nbsp;"]}</div>',
                        '</div>'
                    ].join('');
                }
            },
            listeners: {
                select: function(combo, records, eOpts) {
                    record = combo.up().getRecord();
                    personne = records[0].data;
                    field = combo.up().items;
                    mandat_timelapse = Ext.Date.format(personne.debut, 'd.m.Y') + ' - ' + Ext.Date.format(personne.fin, 'd.m.Y');
                    
                    record.set('activite_nom_abreviation', personne.activite_nom_abreviation);
                    record.set('activite_id', personne.activite_id);
                    record.set('section_code', personne.section_code);
                    record.set('section_id', personne.section_id);
                    record.set('_mandat', mandat_timelapse);
                    
                    field.get(3).setValue(personne.activite_nom_abreviation);
                    field.get(4).setValue(mandat_timelapse);
                    field.get(7).setValue(personne.section_code);
                }
            }
        }
    }*/{
        text: 'Prénom',
        sortable : true,
        dataIndex: 'personne_prenom',
        width: 60
    },{
        text: 'Nom',
        sortable : true,
        dataIndex: 'personne_nom',
        width: 80
    },{
        text: 'Section',
        sortable : true,
        dataIndex: 'section_code',
        width: 45
    }]
},{
    text: 'Mandat',
    columns: [{
        text     : 'Titre académique',
        dataIndex: 'activite_nom_abreviation',
        sortable : true,
    }, {
        text     : 'Durée',
        dataIndex: '_mandat',
        name: '_mandat',
        width: 130,
        sortable : false,
    }]
},{
    text: 'Évaluation',
    columns: [{
        text     : 'Type',
        sortable : true,
        dataIndex: 'evaluation_type_id',
        width: 80,
        xtype: 'ia-combocolumn',
        field: {
            xtype: 'ia-combo',
            editable: false,
            displayField: 'type',
            valueField: 'id',
            allowBlank: false,
            store: new iafbm.store.EvaluationType()
        },
        renderer: function(value, metaData, record, rowIndex, colIndex, store) {
            var combo   = this.editingPlugin.getEditor().getForm().getFields().get(colIndex),
                type_id = record.data.evaluation_type_id;
            
            if(type_id == 0) //doesn't yet affected
                return 'undefined';
            return combo.store.data.items[record.data.evaluation_type_id-1].data.type;
        }
    },{
        text     : 'Début',
        sortable : true,
        dataIndex: 'date_periode_debut',
        width: 70,
        xtype: 'ia-datecolumn',
        field: {
            xtype: 'ia-datefield'
        }
    },{
        text     : 'Fin',
        sortable : true,
        dataIndex: 'date_periode_fin',
        width: 70,
        xtype: 'ia-datecolumn',
        field: {
            xtype: 'ia-datefield'
        }
    },{
        text     : 'Évaluateurs',
        sortable : false,
        dataIndex: '_evaluateurs',
        width: 210,
    },{
        /*text     : 'État',
        sortable : true,
        dataIndex: 'evaluation_etat_id',
        xtype: 'ia-combocolumn',
        field: {
            xtype: 'ia-combo',
            displayField: 'etat',
            valueField: 'id',
            allowBlank: false,
            store: new iafbm.store.EvaluationEtat()
        },
        renderer: function(value, metaData, record, rowIndex, colIndex, store) {
            return record.data.etat;
        },*/
        
        text     : 'État',
        sortable : true,
        dataIndex: 'evaluation_etat_id',
        width: 65,
        xtype: 'ia-combocolumn',
        field: {
            xtype: 'ia-combo',
            editable: false,
            displayField: 'etat',
            valueField: 'id',
            allowBlank: false,
            store: new iafbm.store.EvaluationEtat()
        },
        renderer: function(value, metaData, record, rowIndex, colIndex, store) {
            var combo = this.editingPlugin.getEditor().getForm().getFields().get(colIndex);
            return combo.store.data.items[record.data.evaluation_etat_id-1].data.etat;
        }
    }]
}];