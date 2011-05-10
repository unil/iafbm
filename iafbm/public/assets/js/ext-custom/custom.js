/**
 * Date i18n
**/
Ext.Date.dayNames = [
    "Dimanche",
    "Lundi",
    "Mardi",
    "Mercredi",
    "Jeudi",
    "Vendredi",
    "Samedi"
];
Ext.Date.monthNames = [
    "janvier",
    "février",
    "mars",
    "avril",
    "mai",
    "juin",
    "juillet",
    "août",
    "septembre",
    "octobre",
    "novembre",
    "décembre"
];
Ext.Date.monthNumbers = {
    'jan':0,
    'fév':1,
    'mar':2,
    'avr':3,
    'mai':4,
    'jui':5,
    'juil':6,
    'aou':7,
    'sep':8,
    'oct':9,
    'nov':10,
    'dec':11
};
// This is a custom array for overriden Date.getShortMonthName()
Ext.Date.shortMonthNames = [
    'jan',
    'fév',
    'mar',
    'avr',
    'mai',
    'jui',
    'juil',
    'aou',
    'sep',
    'oct',
    'nov',
    'dec'
];
Ext.Date.getShortMonthName = function(month) {
    return Ext.Date.shortMonthNames[month];
};
Ext.Date.defaultFormat = 'd m Y';

/**
 * Ext classes: customized default
**/

Ext.define('Ext.ia.data.Store', {
    extend:'Ext.data.Store',
    alias: 'store.ia-store',
    pageSize: null,
    autoLoad: true,
    autoSync: false
});

Ext.define('Ext.ia.data.proxy.Rest', {
    extend:'Ext.data.proxy.Rest',
    alias: 'proxy.ia-rest',
    type: 'rest',
    limitParam: 'xlimit',
    startParam: 'xoffset',
    pageParam: undefined,
    reader: {
        type: 'json',
        root: 'items',
        totalProperty: 'xcount'
    },
    writer: {
        root: 'items'
    }
});

Ext.define('Ext.ia.form.field.Date', {
    extend:'Ext.form.field.Date',
    alias: 'widget.ia-datefield',
    format: 'd.m.Y',
    altFormats: 'd.m.Y|d-m-Y|d m Y',
    startDay: 1
});

Ext.define('Ext.ia.form.field.ComboBox', {
    extend:'Ext.form.field.ComboBox',
    alias: 'widget.ia-combo',
    // Workaround for displayField issue (not yet working)
    renderer: function(value, metaData, record, rowIndex, colIndex, store) {
        var store = Ext.data.StoreManager.lookup('store-pays');
        return store.getById(value) ? store.getById(value).get('nom') : '...';
    }
});


/**
 * Business objects
**/

// Models
Ext.define('iafbm.model.Personne', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'},
        {name: 'prenom', type: 'string'},
        {name: 'adresse', type: 'string'},
        {name: 'pays_id', type: 'int'},
        {name: 'tel', type: 'string'},
        {name: 'date_naissance', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'actif', type: 'bool'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url : '/api/personnes', //TODO: this must be dynamic (basepath aware)
    }
});
Ext.define('iafbm.model.Membre', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'personne_id', type: 'int'},
        {name: 'fonction_id', type: 'int'},
        {name: 'commission_id', type: 'int'},
        {name: 'personne_nom', type: 'string'},
        {name: 'personne_prenom', type: 'string'},
        {name: 'actif', type: 'bool'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url : '/api/membres', //TODO: this must be dynamic (basepath aware)
    }
});
Ext.define('iafbm.model.Pays', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'code', type: 'string'},
        {name: 'nom', type: 'string'},
        {name: 'nom_en', type: 'string'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url : '/api/commissions-types', //TODO: this must be dynamic (basepath aware)
    }
});
Ext.define('iafbm.model.Commission', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'},
        {name: 'description', type: 'string'},
        {name: 'commission-type_id', type: 'commission_type_id'},
        {name: 'actif', type: 'bool'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url : '/api/commissions', //TODO: this must be dynamic (basepath aware)
    }
});
Ext.define('iafbm.model.CommissionType', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nom', type: 'string'},
        {name: 'actif', type: 'bool'}
    ],
    validations: [],
    proxy: {
        type: 'ia-rest',
        url : '/api/commissions-types', //TODO: this must be dynamic (basepath aware)
    }
});

// Store
Ext.define('iafbm.store.Personne', {
    extend: 'Ext.ia.data.Store',
    model: 'iafbm.model.Personne'
/*
    //iafbm.model.Personne should be used automagically from the model
    proxy: {
        type: 'ia-rest',
        url : '<?php echo u('api/personnes') ?>',
    },
    pageSize: <?php echo isset($d['pagesize']) ? $d['pagesize'] : 'null'; ?>,
    autoLoad: <?php echo isset($d['autoload']) ? var_export((bool)$d['autoload']) : 'true'; ?>,
    autoSync: <?php echo isset($d['autosync']) ? var_export((bool)$d['autosync']) : 'true'; ?>
*/
});
Ext.define('iafbm.store.Membre', {
    extend: 'Ext.ia.data.Store',
    model: 'iafbm.model.Membre'
});
Ext.define('iafbm.store.Pays', {
    extend: 'Ext.ia.data.Store',
    model: 'iafbm.model.Pays'
});
Ext.define('iafbm.store.Commission', {
    extend: 'Ext.ia.data.Store',
    model: 'iafbm.model.Commission'
});
Ext.define('iafbm.store.CommissionType', {
    extend: 'Ext.ia.data.Store',
    model: 'iafbm.model.CommissionType'
});


//iafbm.Personne.ColumnModel
// TODO: FIXME: find the way to declare an array onReady.
Ext.ns('iafbm.columns');
iafbm.columns.Personne = [{
    header: "Nom",
    dataIndex: 'nom',
    flex: 1,
    editor: {
        xtype: 'textfield',
        allowBlank: false
    }
},{
    header: "Prénom",
    dataIndex: 'prenom',
    flex: 1,
    editor: {
        xtype: 'textfield',
        allowBlank: false
    }
},{
    header: "Adresse",
    dataIndex: 'adresse',
    flex: 1,
    editor: {
        xtype: 'textfield',
        allowBlank: false
    }
},{
    header: "Téléphone",
    dataIndex: 'tel',
    flex: 1,
    editor: {
        xtype: 'textfield',
        allowBlank: false
    }
}, {
    header: "Pays",
    dataIndex: 'pays_id',
    flex: 1,
    editor: {
        xtype: 'ia-combo',
        lazyRender: true,
        typeAhead: true,
        minChars: 1,
        triggerAction: 'all',
        displayField: 'nom',
        valueField: 'id',
        //allowBlank: false,
        store: new iafbm.store.Pays({})
    }
}, {
    header: "Date de naissance",
    dataIndex: 'date_naissance',
    flex: 1,
    renderer: Ext.util.Format.dateRenderer('d F Y'),
    editor: {
        xtype: 'ia-datefield'
    }
},{
    xtype: 'booleancolumn',
    trueText: 'Oui',
    falseText: 'Non',
    header: 'Actif',
    dataIndex: 'actif',
    align: 'center',
    width: 25,
    flex: 1,
    editor: {
        xtype: 'checkbox'
    }
}];
//iafbm.Personne.fields: not used, defined in Model