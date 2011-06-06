<div id="target"></div>


<script type="text/javascript">

Ext.onReady(function() {

    Ext.QuickTips.init();

    var formPanel = Ext.create('Ext.ia.form.Panel', {
        store: new iafbm.store.Personne(),
        loadParams: {id: <?php echo $d['id'] ?>},
        renderTo: 'target',
        title:'Personne',
        frame: true,
        fieldDefaults: {
            labelAlign: 'right',
            msgTarget: 'side'
        },
        items: [{
            xtype: 'fieldset',
            title: 'Contact Information',
            defaultType: 'textfield',
            defaults: {
                width: 280
            },
            items: [{
                    fieldLabel: 'Nom',
                    emptyText: 'Nom',
                    name: 'nom'
                }, {
                    fieldLabel: 'Prénom',
                    emptyText: 'Prénom',
                    name: 'prenom'
                }, {
                    fieldLabel: 'Adresse',
                    emptyText: 'Adresse',
                    name: 'adresse'
                }, {
                    xtype: 'ia-combo',
                    fieldLabel: 'Pays',
                    name: 'pays_id',
                    lazyRender: true,
                    typeAhead: true,
                    minChars: 1,
                    triggerAction: 'all',
                    displayField: 'nom',
                    valueField: 'id',
                    store: new iafbm.store.Pays({})
                }, {
                    fieldLabel: 'Télépone',
                    emptyText: 'Télépone',
                    name: 'tel'
                }, {
                    xtype: 'ia-datefield',
                    fieldLabel: 'Date de naissance',
                    name: 'date_naissance',
                }, {
                    xtype: 'checkboxfield',
                    fieldLabel: 'Actif',
                    name: 'actif'
            }]
        }]
    });

});

</script>