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
Ext.ia.form.Panel
<div id="target-form"></div>

Ext.ia.grid.Panel
<div id="target-grid"></div>

<script>
Ext.onReady(function() {

form = Ext.create('Ext.ia.form.Panel', {
    renderTo: 'target-form',
    store: new iafbm.store.Personne(),
    loadParams: {id:1},
    items: [{
        xtype:'ia-datefield',
        fieldLabel: 'Date',
        name: 'date_naissance'
    }]
});
c = new Ext.ia.grid.EditPanel({
    renderTo: 'target-grid',
    frame: true,
    title: 'Test',
    width: 850,
    height: 200,
    store: new iafbm.store.Personne(),
    columns: [{
        header: "Date de naissance",
        dataIndex: 'date_naissance',
        flex: 1,
        xtype: 'ia-datecolumn',
        editor: {
            xtype: 'ia-datefield'
        }
    }]
});

});
</script>
EOL;
    }

    function remoteComboFormAction() {
return <<<EOL
Ext.ia.form.Panel
<div id="target-form"></div>

<script>
Ext.onReady(function() {

form = Ext.create('Ext.ia.form.Panel', {
    renderTo: 'target-form',
    store: new iafbm.store.Personne(),
    loadParams: {id:1},
    minChars: 1,
    typeAhead: true,
    triggerAction: 'all',
    lazyRender: true,
    items: [{
        xtype: 'textfield',
        fieldLabel: 'Nom',
        name: 'nom'
    },{
        xtype: 'ia-combo',
        fieldLabel: 'Pays',
        name: 'pays_id',
        displayField: 'nom',
        valueField: 'id',
        store: new iafbm.store.Pays()
    }]
});

});
</script>
EOL;
    }

    function remoteComboGridAction() {
$id = @$this->params['id'] ? $this->params['id'] : 1;
return <<<EOL
<div id="target-grid"></div>
<div id="target-combo"></div>
<script>
Ext.onReady(function() {

grid = new Ext.ia.grid.EditPanel({
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
            store: new iafbm.store.Pays(),
        }
    }],
    pageSize: 10
});

form = Ext.create('Ext.ia.form.Panel', {
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

    function deleteCommissionAction() {
        throw new xException("This action is disabled", 403);
        $c = xController::load('commissions', array('id'=>1));
        $r = $c->delete();
        xUtil::pre($r);
    }

    function multiDateAction() {
return <<<EOL
<div id="target"></div>
<script>
Ext.onReady(function() {

form = Ext.create('Ext.ia.form.Panel', {
    renderTo: 'target',
    width: 500,
    items: [{
        xtype: 'ia-multifield',
        fieldLabel: 'Label',
        itemMax: 3,
        itemField: 'date',
        store: new iafbm.store.CommissionTravailEvenement()
    }]
});
form.items.items[0].store.load();

});
</script>
EOL;
    }

    function windowAction() {
return <<<EOL
<a href="javascript:create()">Create window</a>

<script>
function create() {
    w = new Ext.ia.window.Popup({
        title: 'Test popup',
        html: 'Blabla',
        dockedItems: [],
        initComponent: function() {
            this.dockedItems.push({
                xtype: 'toolbar',
                dock: 'top',
                ui: 'footer',
                items: [{
                    xtype: 'button',
                    text: 'Enregistrer',
                    scale: 'medium',
                    handler: function() { }
                }]
            });
tt=this;
            var me = this;
            me.callParent();
        }
    });
}
</script>
EOL;
    }

    function modelWhereAction() {
        $controller = xController::load('personnes-adresses', array(
            'personne_id' => 1,
            'query' => '25',
            'xwhere' => 'query'
        ));
        xUtil::pre($controller->get());
    }

    function versionComboAction() {
return <<<EOL
<div id="target"></div>
<script>
Ext.onReady(function() {

form = Ext.create('Ext.ia.Versioning', {
    renderTo: 'target',
    modelname: 'personne',
    modelid: 1
});

});
</script>
EOL;
    }

    function formTogglableAction() {
return <<<EOL
<div id="target"></div>
<script>
Ext.onReady(function() {

form = Ext.create('Ext.ia.Versioning', {
    renderTo: 'target',
    modelname: 'personne',
    modelid: 1
});

});
</script>
EOL;
    }

    function dataWhereAction() {
        $s1 = array(
            'field',
            array('field_x', 'field_y'),
            'table1' => array('field1', 'field2'),
            'table2' => 'field',
            'table3' => array('field_n', 'alias' => 'field_m')
        );
        //
        $f1 = array(
            'table1',
            'alias' => 'table2'
            // TODO: SUBSELECT definition?
        );
        //
        $j1 = array(
            array(
                array('table.local' => 'table.foreign')
            ),
            array(
                'NATURAL JOIN' => array('table.local_field' => 'table.foreign')
            ),
            array(
                'LEFT OUTER JOIN' => array(
                    array('table1.local_field' => 'table1.foreign'),
                    array('table2.local_field' => 'table2.foreign')
                )
            )
        );
        //
        $w1 = array(
            'a' => 1,
            'b' => 2,
            'c' => 3
        );
        $w2 = array(
            array(
                'a' => 1,
                'b' => 2
            )
        );
        $w3 = array(
            'x' => 0,
            array(
                'a' => 1,
                'b' => 2
            )
        );
        $w4 = array(
            array(
                'x' => 0
            ),
            array(
                array('OR' => array(
                    'a' => 1,
                    'b' => 2
                )),
                array(
                    'c' => 3
                ),
                array('OR' => array(
                    'a' => 1,
                    'b' => 2
                ))
            )
        );
        //
        $g1 = array(
            'table1.field1',
            'table2.field2',
            //'table3' => array('field31', 'field32')
            //'field0' // no table specified behaviour?
        );
        //
        $o1 = array(
            'table1.field1 ASC',
            'table2.field2 DESC',
            //'table3' => array('field31 ASC', 'field32 DESC')
            //'field0' // no table specified behaviour?
        );
        //
        $full = array(
            'select' => $s1,
            'from' => $f1,
            'joins' => $j1,
            'where' => $w4,
            'group' => $g1,
            'order' => $o1,
            //'having' => array()
        );
        //
        // Class test (xSql)
        $parser = new xSqlRequestParserSql($full);
        $sql = $parser->parse();
        xUtil::pre((string)$sql);
        xUtil::pre($full);
        xUtil::pre($sql);
/*
        //
        // Class test (xSqlSelect)
        $s = $s1;
        xUtil::pre($s);
        $parser = new xSqlRequestParserSelect($s);
        $sql = $parser->parse();
        xUtil::pre((string)$sql);
        //
        // Class test (simple)
        $w = $w4;
        xUtil::pre($w);
        $parser = new xSqlRequestParserWhere($w);
        $sql = $parser->parse();
        xUtil::pre((string)$sql);
        //
        // Class test (xSqlFrom)
        $f = $f1;
        xUtil::pre($f);
        $parser = new xSqlRequestParserFrom($f);
        $sql = $parser->parse();
        xUtil::pre((string)$sql);
        //
        // Class test (xSqlFrom)
        $j = $j1;
        xUtil::pre($j);
        $parser = new xSqlRequestParserJoins($j);
        $joins = $parser->parse();
        xUtil::pre(implode(",\n", $joins));
        xUtil::pre($joins);
        //
        // Class test (xSqlGroup)
        $g = $g1;
        xUtil::pre($g);
        $parser = new xSqlRequestParserGroup($g);
        $group = $parser->parse();
        xUtil::pre((string)$group);
        xUtil::pre($group);
        //
        // Class test (xSqlOrder)
        $o = $o1;
        xUtil::pre($o);
        $parser = new xSqlRequestParserOrder($o);
        $order = $parser->parse();
        xUtil::pre((string)$order);
        xUtil::pre($order);
*/
        //
        die();
    }
}