<div id="editor-grid"></div>

<script type="text/javascript">

Ext.require(['Ext.data.*', 'Ext.grid.*']);

/* Models definition */
<?php foreach ($d['models'] as $model) echo "{$model}\r\n" ?>

Ext.onReady(function(){

    Ext.QuickTips.init();

    // Overrides Extjs default mapping for action <> HTTP method.
/*
    Ext.override(Ext.data.proxy.Rest, {
        actionMethods: {
            create : 'PUT',
            read   : 'GET',
            update : 'POST',
            destroy: 'DELETE'
        }
    });
*/

    var store = new Ext.data.Store({
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
    });

    var rowEditing = new Ext.grid.plugin.RowEditing();

//    var grid = Ext.create('Ext.grid.Panel', {
    var grid = new Ext.grid.Panel({
        id: '<?php echo "{$d["id"]}_grid" ?>',
        title: '<?php echo $d["title"] ?>',
        iconCls: 'icon-user',
        renderTo: 'editor-grid',
        loadMask: true,
        width: 880,
        height: 300,
        frame: true,
        plugins: [rowEditing],
        store: store,
        columns: <?php echo $d['columns'] ?>,
        dockedItems: [{
            xtype: 'toolbar',
            items: [{
                text: 'Add',
                iconCls: 'icon-add',
                handler: function(){
                    // empty record
                    store.insert(0, new <?php echo $d['model'] ?>());
                    rowEditing.startEdit(0, 0);
                }
            }, '-', {
                text: 'Delete',
                iconCls: 'icon-delete',
                handler: function(){
                    var selection = grid.getView().getSelectionModel().getSelection()[0];
                    if (selection) {
                        store.remove(selection);
                    }
                }
            }, '->', '-', 'Rechercher',
            new Ext.ux.form.SearchField({
                store: store,
                emptyText: 'Mots-clés'
            })]
        }],
        bbar: new Ext.PagingToolbar({
            store: store,
            displayInfo: true,
            displayMsg: 'Eléments {0} à {1} sur {2}',
            emptyMsg: "Pas d'éléments à afficher",
            items:[],
            //plugins: Ext.create('Ext.ux.ProgressBarPager', {})
        })
    });
});

</script>