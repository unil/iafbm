<div id="target"></div>


<script type="text/javascript">

Ext.onReady(function() {

    Ext.QuickTips.init();

    var formPanel = Ext.create('Ext.form.Panel', {
        renderTo: 'target',
        frame: true,
        title:'Personne',
        width: 340,
        bodyPadding: 5,
        waitMsgTarget: true,
        fieldDefaults: {
            labelAlign: 'right',
            labelWidth: 85,
            msgTarget: 'side'
        },
        url: '<?php echo u("api/personnes/{$d['id']}") ?>',
        method: 'get',
        reader: Ext.create('Ext.data.reader.Json', {
            model: 'Personne',
            root: 'items'
        }),
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
                }, /*{
                    xtype: 'combobox',
                    fieldLabel: 'State',
                    name: 'state',
                    store: Ext.create('Ext.data.ArrayStore', {
                        fields: ['abbr', 'state'],
                        data : Ext.example.states // from states.js
                    }),
                    valueField: 'abbr',
                    displayField: 'state',
                    typeAhead: true,
                    queryMode: 'local',
                    emptyText: 'Select a state...'
                }, */{
                    fieldLabel: 'Télépone',
                    emptyText: 'Télépone',
                    name: 'tel'
                }, {
                    xtype: 'ia-datefield',
                    fieldLabel: 'Date de naissance',
                    name: 'date_naissance',
                    allowBlank: false
                }, {
                    xtype: 'checkboxfield',
                    fieldLabel: 'Actif',
                    name: 'actif'
            }]
        }],
        listeners: {
            afterrender: function() {
                var id = <?php echo $d['id'] ?>;
                iafbm.model.Personne.load(id, {
                    success: function(record, operation) {
                        formPanel.loadRecord(record);
                    }
                });
            }
        },
        buttons: [{
            text: 'Sauvegarder',
            //disabled: true,
            formBind: true,
            handler: function() {
                var form = this.up('form').getForm();
                if (form.isValid()) {
                    var values = Ext.apply(form.getRecord().data, form.getValues());
                    var model = new iafbm.model.Personne(values);
                    model.save();
                }
            }
        }]
    });

});

</script>