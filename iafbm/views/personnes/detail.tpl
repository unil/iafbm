<div id="form"></div>


<script type="text/javascript">

Ext.onReady(function() {

    Ext.QuickTips.init();

    var formPanel = Ext.create('Ext.form.Panel', {
        renderTo: 'form',
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
        listeners: {
            afterrender: function() { console.log('TODO: autoload logic') }
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
                    emptyText: 'Actif',
                    name: 'actif'
            }]
        }],
        buttons: [{
            text: 'Load',
            handler: function() {
                // TODO: run this on form load
                var id = <?php echo $d['id'] ?>;
                iafbm.model.Personne.load(id, {
                    success: function(record, operation) {
                        formPanel.loadRecord(record);
                    }
                });
            }
        }, {
            text: 'Sauvegarder',
            //disabled: true,
            formBind: true,
            handler: function() {
                var form = this.up('form').getForm();
                if (form.isValid()) {
                    var values = Ext.apply(form.getRecord().data, form.getValues());
                    new iafbm.model.Personne(values).save();
                }
            }
        }]
    });

});

</script>