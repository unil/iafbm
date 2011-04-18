<div id="editor-grid"></div>

<script type="text/javascript">

// Grid CRUD example
// http://loianegroner.com/2010/03/extjs-and-spring-mvc-framework-crud-datagrid-example/

Ext.onReady(function(){
    Ext.QuickTips.init();

    var pagesize = 10;

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
        root: 'items',
        idProperty: 'id',
        totalProperty: 'xcount',
        successProperty: 'xsuccess',
        // use an Array of field definition objects to implicitly create a Record constructor
        fields: <?php echo $d['fields'] ?>
    });

    var proxy = new Ext.data.HttpProxy({
        api: {
            read: {url: '<?php echo $d["url"] ?>', method: 'GET'},
            create: {url: '<?php echo $d["url"] ?>', method: 'PUT'},
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
        restful: true,
        baseParams: {xoffset:0, xlimit:pagesize}
    });

    var cm = new Ext.grid.ColumnModel({
        defaults: {
            sortable: true // columns are not sortable by default
        },
        columns: <?php echo $d['columns'] ?>
    });

    var editor = new Ext.ux.grid.RowEditor({
        saveText: 'Appliquer',
        cancelText: 'Annuler',
        listeners: {
            afteredit: function() { /*console.log('afteredit EVENT!')*/ }
        }
    });

    var toolbar_data_actions = [{
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
    }];

    var toolbar_search = ['Rechercher:', {
        xtype:'textfield',
        enableKeyEvents: true,
        id: 'query',
        listeners: {
            'keyup': function(c, e) {
                //if (e.getKey() !== e.ENTER) return;
                var store = Ext.getCmp('<?php echo "{$d['id']}_grid" ?>').store;
                //store.load({params:{'query':this.getValue()}});
                if (this.getValue().length > 0) store.setBaseParam('query', this.getValue());
                else delete store.baseParams.query;
                store.load();
            }
        }
    }, {
        xtype: 'button',
        text: 'Go',
        handler: function() { /* TODO */ }
    }];

    var paging_toolbar = new Ext.PagingToolbar({
        pageSize: pagesize,
        store: store,
        paramNames: {
            start : 'xoffset',
            limit : 'xlimit'
        },
        displayInfo: true,
        displayMsg: 'Eléments {0} à {1} sur {2}',
        emptyMsg: "Aucun élément à afficher",
    });


    // create grid
    var grid = new Ext.grid.EditorGridPanel({
        id: '<?php echo "{$d["id"]}_grid" ?>',
        title: '<?php echo $d["title"] ?>',
        height: 500,
        width: 900,
        frame: true,
        renderTo: 'editor-grid',
        loadMask: true,
        store: store,
        cm: cm,
        sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
        plugins: [editor],
        tbar: [].concat(toolbar_data_actions, [{xtype:'tbspacer'}], toolbar_search),
        bbar: paging_toolbar,
        viewConfig: {
            forceFit: true // Expands columns width to fit the grid
        }
    });

    store.load();
    //grid.getView().refresh(); // <---- this makes the combos show up the labels!
});

</script>