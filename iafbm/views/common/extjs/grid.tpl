<div id="editor-grid"></div>

<script type="text/javascript">

Ext.require(['Ext.data.*', 'Ext.grid.*']);


Ext.onReady(function(){

    Ext.QuickTips.init();

    //    var grid = Ext.create('Ext.grid.Panel', {
    var <?php echo $d['var'] ?> = new Ext.grid.Panel({
        id: '<?php echo "{$d["id"]}_grid" ?>',
        title: '<?php echo $d["title"] ?>',
        iconCls: 'icon-user',
        renderTo: '<?php echo $d["renderTo"] ?>',
        loadMask: true,
        width: 880,
        height: 300,
        frame: true,
        plugins: [new Ext.grid.plugin.CellEditing({clicksToEdit:1,pluginId:'rowediting'})],
        store: new iafbm.store.<?php echo $d['model'] ?>({
            pageSize: 10,
            autoLoad: true,
            autoSync: true
        }),
        columns: iafbm.columns.<?php echo $d['model'] ?>,
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