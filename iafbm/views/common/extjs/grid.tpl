<div id="editor-grid"></div>

<script type="text/javascript">

Ext.require(['Ext.data.*', 'Ext.grid.*']);


Ext.onReady(function(){

    Ext.QuickTips.init();

/*
    // Overrides Extjs default mapping for action <> HTTP method.
    Ext.override(Ext.data.proxy.Rest, {
        actionMethods: {
            create : 'PUT',
            read   : 'GET',
            update : 'POST',
            destroy: 'DELETE'
        }
    });
*/

    /* Models definition */
    <?php foreach ($d['models'] as $model) echo "{$model}\r\n" ?>

//    var grid = Ext.create('Ext.grid.Panel', {
    var <?php echo $d['var'] ?> = a = new Ext.grid.Panel({
        id: '<?php echo "{$d["id"]}_grid" ?>',
        title: '<?php echo $d["title"] ?>',
        iconCls: 'icon-user',
        renderTo: '<?php echo $d["renderTo"] ?>',
        loadMask: true,
        width: 880,
        height: 300,
        frame: true,
        plugins: [new Ext.grid.plugin.RowEditing({pluginId:'rowediting'})],
        store: new Ext.data.Store({
            model: '<?php echo $d["model"] ?>',
            proxy: {
                type: 'rest',
                url : '<?php echo $d["url"] ?>',
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
            },
            pageSize: 10,
            autoLoad: true,
            autoSync: true
        }),
        columns: <?php echo $d['columns'] ?>,
        dockedItems: [{
            xtype: 'toolbar',
            items: [{
                text: 'Ajouter',
                iconCls: 'icon-add',
                handler: function(){
                    // empty record
                    this.up('gridpanel').store.insert(0, new <?php echo $d['model'] ?>());
                    this.up('gridpanel').getPlugin('rowediting').startEdit(0, 0);
                }
            }, '-', {
                text: 'Supprimer',
                iconCls: 'icon-delete',
                handler: function(){
                    var selection = grid.getView().getSelectionModel().getSelection()[0];
                    if (selection) {
                        this.up('gridpanel').store.remove(selection);
                    }
                }
            }, '->', '-', 'Rechercher',
            new Ext.ux.form.SearchField({
                store: null,
                emptyText: 'Mots-clés',
                listeners: {
                    beforerender: function() { this.store = this.up('gridpanel').store }
                }
            })]
        }],
        bbar: new Ext.PagingToolbar({
            store: null,
            displayInfo: true,
            displayMsg: 'Eléments {0} à {1} sur {2}',
            emptyMsg: "Aucun élément à afficher",
            items:[],
            listeners: {
                // Wait for render time so that the grid store is created
                // and ready to be bound to the pager
                beforerender: function() { this.bindStore(this.up('gridpanel').store) }
            }
            //plugins: Ext.create('Ext.ux.ProgressBarPager', {})
        })
    });
});

</script>