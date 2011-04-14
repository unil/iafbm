<div id="editor-grid"></div>

<script type="text/javascript">

// Grid CRUD example
// http://loianegroner.com/2010/03/extjs-and-spring-mvc-framework-crud-datagrid-example/

Ext.onReady(function(){

    /**
     * Events bound to any DataProxy child instance
     */
    Ext.data.DataProxy.addListener('exception', function(proxy, type, action, options, res) {
        Ext.MessageBox.alert([
            'Erreur', 'Une erreur est survenue :(',
            '<br>',
            res.toString()
        ].join(''));
    });

/*
    var writer = new Ext.data.XmlWriter({
        xmlEncoding: 'UTF-8',
        encode: true,
        writeAllFields: false
    });
*/
    var writer = new Ext.data.JsonWriter({
        encode: false,
        writeAllFields: true
    });

//    var reader = new Ext.data.XmlReader({
    var reader = new Ext.data.JsonReader({
        // records will have a 'item' tag
        record: 'item',
        successProperty: 'xsuccess',
        idProperty: 'id',
        // use an Array of field definition objects to implicitly create a Record constructor
        fields: <?php echo $d['fields'] ?>
    });

    var proxy = new Ext.data.HttpProxy({
        api: {
            read : {url: '<?php echo $d["url"] ?>', method: 'GET'},
            create : {url: '<?php echo $d["url"] ?>', method: 'PUT'},
            update: {url: '<?php echo $d["url"] ?>', method: 'POST'},
            destroy: {url: '<?php echo $d["url"] ?>', method: 'DELETE'}
        }
    });

    var store = new Ext.data.Store({
        id: '<?php echo "{$d['id']}_store" ?>',
        proxy: proxy,
        reader: reader,
        writer: writer,  // <-- plug a DataWriter into the store just as you would a Reader
        autoSave: false, // <-- true would delay executing create, update, destroy requests until specifically told to do so with some [save] buton.
        restful: true
    });

    var cm = new Ext.grid.ColumnModel({
        defaults: {
            sortable: true // columns are not sortable by default
        },
        columns: <?php echo $d['columns'] ?>
    });

    var plugin_editor = new Ext.ux.grid.RowEditor({
        saveText: 'Appliquer',
        cancelText: 'Annuler',
        listeners: {
            afteredit: function() { /*console.log('afteredit EVENT!')*/ }
        }
    });

    // create grid
    var grid = new Ext.grid.GridPanel({
        id: '<?php echo "{$d["id"]}_grid" ?>',
        store: store,
        renderTo: 'editor-grid',
        cm: cm,
        sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
        plugins: [plugin_editor],
        title: '<?php echo $d["title"] ?>',
        height: 500,
        width: 900,
        frame: true,
        tbar: [{
            iconCls: 'icon-add',
            text: 'Ajouter',
            handler: function(){
                var Item = grid.getStore().recordType;
                var e = new Item({
                    nom: 'Nom',
                    prenom: 'Prénom'
                });
                editor.stopEditing();
                store.insert(0, e);
                grid.getView().refresh();
                grid.getSelectionModel().selectRow(0);
                editor.startEditing(0);
            }
        },{
            iconCls: 'icon-delete',
            text: 'Supprimer',
            handler: function(){
                editor.stopEditing();
                var s = grid.getSelectionModel().getSelections();
                for(var i = 0, r; r = s[i]; i++){
                    store.remove(r);
                }
            }
        },{
            iconCls: 'icon-save',
            text: 'Sauvergarder toutes les modifications',
            handler: function(){
                store.save();
            }
        }],
        viewConfig: {
            forceFit: true // Expands columns width to fit the grid
        }
    });

    store.load();
});

</script>