<?php

class SandboxController extends xWebController {

    function defaultAction() {
        foreach (get_class_methods($this) as $method)
            if (substr($method, -strlen('Action')) == 'Action')
                $actions[] = substr($method, 0, -strlen('Action'));
        foreach ($actions as $action)
            @$html .= "<a href=\"".xUtil::url("sandbox/do/{$action}")."\">{$action}</a><br>";
        return $html;
    }

    function selectiongridAction() {
return <<<EOL
<div id="target"></div>
<script>
Ext.onReady(function() {

c = new Ext.ia.selectiongrid.Panel({
    renderTo: 'target',
    frame: true,
    title: 'Test',
    width: 500,
    height: 300,
    combo: {
        store: new iafbm.store.Personne(),
    },
    grid: {
        store: new iafbm.store.Membre(),
        columns: iafbm.columns.Membre
    }
});

});
</script>
EOL;
    }

    function editgridAction() {
return <<<EOL
<div id="target"></div>
<script>
Ext.onReady(function() {

c = new Ext.ia.grid.EditPanel({
    renderTo: 'target',
    frame: true,
    title: 'Test',
    width: 500,
    height: 300,
    store: new iafbm.store.Personne(),
    columns: iafbm.columns.Personne
});

});
</script>
EOL;
    }

    function dateformatAction() {
return <<<EOL
<div id="target"></div>
<script>
Ext.onReady(function() {

//form = Ext.create('Ext.ia.form.Panel', {
form = Ext.create('Ext.form.Panel', {
    renderTo: 'target',
    store: Ext.create('Ext.data.ArrayStore', {
        fields: [
            {name: 'date', type: 'date', dateFormat: 'Y-m-d'},
        ],
        data: [['1979-11-10']]
    }),
    items: [{
        xtype:'ia-datefield',
        fieldLabel: 'Date',
        name: 'date'
    }]
});

console.log('form.loadRecord()');
form.loadRecord(form.store.getAt(0));
console.log('form.getValues()');
values = form.getValues();
console.log('values:', values);
//console.log('record.set()');
//form.store.getAt(0).set(values);
console.log("record.set({date: 'what format?')");
form.store.getAt(0).set({date: '2000-10-11'});
console.log("record.get('date')", form.store.getAt(0).get('date'));
console.log('----8<------------------');

});
</script>
EOL;
    }

    function remoteComboGridAction() {
$id = $this->params['id'];
return <<<EOL
<div id="target-grid"></div>
<div id="target-combo"></div>
<script>
Ext.onReady(function() {

p = new Ext.ia.grid.EditPanel({
    renderTo: 'target-grid',
    frame: false,
    width: 880,
    height: 330,
    store: new iafbm.store.Personne(),
    columns: [{
        header: "Pays",
        dataIndex: 'pays_id',
        flex: 1,
        xtype: 'ia-combocolumn',
        editor: {
            xtype: 'ia-combo',
            displayField: 'nom',
            valueField: 'id',
            //allowBlank: false,
            store: new iafbm.store.Pays({autoLoad:true}),
        }
    }],
    pageSize: 10
});

form_apercu = Ext.create('Ext.ia.form.Panel', {
    renderTo: 'target-combo',
    id: 'test-grid',
    loadParams: {id: {$id}},
    defaults: {
        flex: 1,
        anchor: '100%'
    },
    items: [new Ext.ia.selectiongrid.Panel({
        title: 'Composition',
        width: 857,
        height: 200,
        plugins: [new Ext.grid.plugin.CellEditing({clicksToEdit:1})],
        combo: {
            store: new iafbm.store.Personne(),
        },
        grid: {
            store: new iafbm.store.Membre(),
            columns: iafbm.columns.Membre
        },
        makeData: function(record) {
            return {
                personne_id: record.get('id'),
                fonction_id: 1,
                commission_id: {$id},
                actif: 1
            }
        }
    })]
});


});
</script>
EOL;
    }
}