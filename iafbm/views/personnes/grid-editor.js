// Grid CRUD example
// http://loianegroner.com/2010/03/extjs-and-spring-mvc-framework-crud-datagrid-example/

Ext.onReady(function(){

    /*
    var writer = new Ext.data.JsonWriter({
        encode: true,
        writeAllFields: false
    });
    */


    var writer = new Ext.data.XmlWriter({
        xmlEncoding: 'UTF-8',
        encode: true,
        writeAllFields: false
    });
    var writer = new Ext.data.JsonWriter({
        root: 'data',
        encode: false
    });

//    var reader = new Ext.data.XmlReader({
    var reader = new Ext.data.JsonReader({
        // records will have a 'plant' tag
        record: 'item',
        successProperty: 'success',
        idProperty: 'id',
        // use an Array of field definition objects to implicitly create a Record constructor
        fields: [
            // the 'name' below matches the tag name to read, except 'availDate'
            // which is mapped to the tag 'availability'
            {name: 'id', type: 'int'},
            {name: 'nom', type: 'string'},
            {name: 'prenom', type: 'string'},
            {name: 'adresse', type: 'string'},
            {name: 'tel', type: 'string'},
            {name: 'date_naissance', type: 'date', dateFormat: 'Y-d-m'},
    /*
            {name: 'botanical', type: 'string'},
            {name: 'light'},
            {name: 'price', type: 'float'},
            // dates can be automatically converted by specifying dateFormat
            {name: 'availDate', mapping: 'availability', type: 'date', dateFormat: 'm/d/Y'},
            {name: 'indoor', type: 'bool'}
    */
        ]
    });

    var cm = new Ext.grid.ColumnModel({
        defaults: {
            sortable: true // columns are not sortable by default
        },
        columns: [{
            header: "Nom",
            width: 170,
            sortable: true,
            dataIndex: 'nom',
            editor: {
                xtype: 'textfield',
                allowBlank: false
            }
        },{
            header: "Prénom",
            width: 170,
            sortable: true,
            dataIndex: 'prenom',
            editor: {
                xtype: 'textfield',
                allowBlank: false
            }
        },{
            header: "Adresse",
            width: 170,
            sortable: true,
            dataIndex: 'adresse',
            editor: {
                xtype: 'textfield',
                allowBlank: false
            }
        },{
            header: "Téléphone",
            width: 170,
            sortable: true,
            dataIndex: 'tel',
            editor: {
                xtype: 'textfield',
                allowBlank: false
            }
        },{
            header: "Date de naissance",
            width: 170,
            sortable: true,
            dataIndex: 'date_naissance',
            editor: {
                xtype: 'datefield',
                startDay: 1,
                //allowBlank: false,
                format: 'd.m.Y'
            }
        }]
    });

    var proxy = new Ext.data.HttpProxy({
        api: {
            read : {url: '/api/personnes/get', method: 'GET'},
            create : {url: '/api/personnes/put', method: 'PUT'},
            update: {url: '/api/personnes/post', method: 'POST'},
            destroy: {url: '/api/personnes/delete', method: 'DELETE'}
        }
    });
    Ext.data.DataProxy.addListener('exception', function(proxy, type, action, options, res) {
        Ext.MessageBox.alert([
            'Erreur', 'Une erreur est survenue :(',
            '<br>',
            res.toString()
        ].join(''));
    });

    var store = new Ext.data.Store({
        id: 'personnes',
        proxy: proxy,
        reader: reader,
        writer: writer,  // <-- plug a DataWriter into the store just as you would a Reader
        autoSave: false, // <-- false would delay executing create, update, destroy requests until specifically told to do so with some [save] buton.
        restful: true
    });

    var editor = new Ext.ux.grid.RowEditor({
        saveText: 'Appliquer',
        cancelText: 'Annuler'
    });

    // create grid
    var grid = new Ext.grid.GridPanel({
        store: store,
        renderTo: 'editor-grid',
        cm: cm,
        plugins: [editor],
        title: 'Personnes',
        height: 300,
        width: '100%',
        frame:true,
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
        }]
    });

    store.load();

});









// ExtJS official example (no CRUD, read only)
// http://dev.sencha.com/deploy/dev/examples/grid/edit-grid.html


Ext.onReady(function(){}, function(){

    /**
     * Handler specified for the 'Available' column renderer
     * @param {Object} value
     */
    function formatDate(value){
        return value ? value.dateFormat('M d, Y') : '';
    }

    // shorthand alias
    var fm = Ext.form;

    // the column model has information about grid columns
    // dataIndex maps the column to the specific data field in
    // the data store (created below)
    var cm = new Ext.grid.ColumnModel({
        // specify any defaults for each column
        defaults: {
            sortable: true // columns are not sortable by default
        },
        columns: [{
            id: 'nom',
            header: 'Nom',
            dataIndex: 'nom',
            width: 220,
            // use shorthand alias defined above
            editor: new fm.TextField({
                allowBlank: false
            })
        }, {
            id: 'prenom',
            header: 'Prénom',
            dataIndex: 'prenom',
            width: 220,
            // use shorthand alias defined above
            editor: new fm.TextField({
                allowBlank: false
            })
        }/*, {
            header: 'Prénom',
            dataIndex: 'light',
            width: 130,
            editor: new fm.ComboBox({
                typeAhead: true,
                triggerAction: 'all',
                // transform the data already specified in html
                transform: 'light',
                lazyRender: true,
                listClass: 'x-combo-list-small'
            })
        }, {
            header: 'Price',
            dataIndex: 'price',
            width: 70,
            align: 'right',
            renderer: 'usMoney',
            editor: new fm.NumberField({
                allowBlank: false,
                allowNegative: false,
                maxValue: 100000
            })
        }, {
            header: 'Available',
            dataIndex: 'availDate',
            width: 95,
            renderer: formatDate,
            editor: new fm.DateField({
                format: 'm/d/y',
                minValue: '01/01/06',
                disabledDays: [0, 6],
                disabledDaysText: 'Plants are not available on the weekends'
            })
        }, {
            xtype: 'checkcolumn',
            header: 'Indoor?',
            dataIndex: 'indoor',
            width: 55
        }*/]
    });

    // create the Data Store
    var store = new Ext.data.Store({
        // destroy the store if the grid is destroyed
        autoDestroy: true,

        // load remote data using HTTP
        url: '/api/personnes/get',

        // specify a XmlReader (coincides with the XML format of the returned data)
        reader: new Ext.data.XmlReader({
            // records will have a 'plant' tag
            record: 'item',
            // use an Array of field definition objects to implicitly create a Record constructor
            fields: [
                // the 'name' below matches the tag name to read, except 'availDate'
                // which is mapped to the tag 'availability'
                {name: 'nom', type: 'string'},
                {name: 'prenom', type: 'string'},
/*
                {name: 'botanical', type: 'string'},
                {name: 'light'},
                {name: 'price', type: 'float'},
                // dates can be automatically converted by specifying dateFormat
                {name: 'availDate', mapping: 'availability', type: 'date', dateFormat: 'm/d/Y'},
                {name: 'indoor', type: 'bool'}
*/
            ]
        }),

        sortInfo: {field:'nom', direction:'ASC'}
    });

    // create the editor grid
    var grid = new Ext.grid.EditorGridPanel({
        store: store,
        cm: cm,
        renderTo: 'editor-grid',
        width: 600,
        height: 300,
        //autoExpandColumn: 'nom', // column with this id will be expanded
        title: 'Personnes',
        frame: true,
        clicksToEdit: 1,
        tbar: [{
            text: 'Ajouter',
            handler : function(){
                // access the Record constructor through the grid's store
                var Plant = grid.getStore().recordType;
                var p = new Plant({
                    nom: 'Nom',
                    prenom: 'Prénom'/*,
                    light: 'Mostly Shade',
                    price: 0,
                    availDate: (new Date()).clearTime(),
                    indoor: false
                    */
                });
                grid.stopEditing();
                store.insert(0, p);
                grid.startEditing(0, 0);
            }
        }]
    });

    // manually trigger the data store load
    store.load({
  /*
        // store loading is asynchronous, use a load listener or callback to handle results
        callback: function(){
            Ext.Msg.show({
                title: 'Store Load Callback',
                msg: 'store was loaded, data available for processing',
                modal: false,
                icon: Ext.Msg.INFO,
                buttons: Ext.Msg.OK
            });
        }
*/
    });
});