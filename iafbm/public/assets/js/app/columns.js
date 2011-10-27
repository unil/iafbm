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
    header: "Titres académiques",
    flex: 1,
    xtype: 'ia-listcolumn',
    dataIndex: 'id',
    store: new iafbm.store.PersonneFonction(),
    filterField: 'personne_id',
    displayField: 'titre-academique_abreviation'
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
    header: "Titre",
    dataIndex: 'titre-academique_id',
    width: 150,
    xtype: 'ia-combocolumn',
    editor: {
        xtype: 'ia-combo',
        displayField: 'abreviation',
        valueField: 'id',
        allowBlank: false,
        store: new iafbm.store.TitreAcademique()
    }
}, {
    header: "Nom",
    dataIndex: 'personne_nom',
    width: 125,
}, {
    header: "Prénom",
    dataIndex: 'personne_prenom',
    width: 125,
}, {
    header: "Dépt / Service",
    dataIndex: 'departement_id',
    width: 150,
    xtype: 'ia-combocolumn',
    editor: {
        xtype: 'ia-combo',
        displayField: 'nom',
        valueField: 'id',
        store: new iafbm.store.Departement()
    }
}, {
    header: "Fonction",
    dataIndex: 'fonction_id',
    flex: 1,
    xtype: 'ia-combocolumn',
    editor: {
        xtype: 'ia-combo',
        displayField: 'nom',
        valueField: 'id',
        allowBlank: false,
        store: new iafbm.store.CommissionFonction()
    }
}];

iafbm.columns.Candidat = [{
    xtype: 'ia-actioncolumn-detailform',
    form: iafbm.form.Candidat
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
        displayField: 'genre',
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
    dataIndex: 'commission-type_id',
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
    width: 150,
}, {
    header: "Etat",
    dataIndex: 'commission-etat_id',
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

iafbm.columns.TitreAcademique = [{
    header: "Abréviation",
    dataIndex: 'abreviation',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
}, {
    header: "Nom",
    dataIndex: 'nom',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
}];

iafbm.columns.FonctionHospitaliere = [{
    header: "Nom",
    dataIndex: 'nom',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
}];

iafbm.columns.Departement = [{
    header: "Nom",
    dataIndex: 'nom',
    flex: 1,
    field: {
        xtype: 'textfield',
        allowBlank: false
    }
}];