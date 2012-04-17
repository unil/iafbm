<h1>Recherche</h1>

<div id="target"></div>


<script type="text/javascript">

Ext.onReady(function() {

    var entities_store = new Ext.data.ArrayStore({
        fields: ['model', 'label'],
        data: [
            ['Personne', 'Personnes'],
            ['Candidat', 'Candidats'],
            ['Commission', 'Commissions'],
            ['CommissionMembre', 'Membres de commissions'],
            ['Activite', 'Fonctions'],
            ['PersonneAdresse', 'Adresses de personnes']
        ]
    });

    var search = Ext.create('Ext.panel.Panel', {
        layout: 'hbox',
        defaults: { labelSeparator: null },
        border: false,
        items: [{
            xtype: 'textfield',
            flex: 1,
            fieldLabel: 'Rechercher',
            labelWidth: 60,
            emptyText: 'Mots-clés',
            listeners: {
                specialkey: function(el, e) {
                    if (e.keyCode == e.ENTER) {
                        var button = this.up('panel').down('button');
                        button.handler();
                    }
                },
                afterrender: function() {
                    this.focus();
                }
            }
        }, {
            xtype: 'combo',
            width: 250,
            margin: '0 0 0 3px',
            fieldLabel: 'dans',
            labelWidth: 25,
            store: entities_store,
            valueField: 'model',
            displayField: 'label',
            editable: false,
            value: 'Personne'
        }, {
            xtype: 'button',
            width: 100,
            margin: '0 0 0 3px',
            text: 'Rechercher',
            handler: function() {
                var combo = this.up('panel').down('combo'),
                    query = this.up('panel').down('textfield'),
                    grid = this.up('panel').up('panel').down('grid');
                var modelname = combo.getValue(),
                    xquery = query.getValue();
                grid.switchToModel(modelname, {xquery:xquery});
            }
        }, {
            xtype: 'button',
            text: '?',
            handler: function() {
                var modelname = this.up('panel').down('combo').getValue(),
                    url = iafbm.model[modelname].proxy.url+'?xmethod=queryfields';
                new Ext.window.Window({
                    border: false,
                    title: 'Aide sur la recherche',
                    html: '<iframe src="'+url+'" style="border:none; width:100%; height:100%"/>'
                }).show();
            }
        }]
    });

    var result = Ext.create('Ext.grid.Panel', {
        store: null,
        columns: [],
        switchToModel: function(modelname, params) {
            var params = params || {};
            this.reconfigure(
                new iafbm.store[modelname],
                iafbm.columns[modelname]
            );
            this.store.load({params: params});
        }
    });

    var panel = Ext.create('Ext.panel.Panel', {
        renderTo: 'target',
        border: false,
        items: [search, result]
    });
});

</script>
