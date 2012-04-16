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

    function DataWhereAction() {
        $p1 = array(
            'a' => 1,
            'b' => 2,
            'c' => 3
        );
        $p2 = array(
            array(
                'a' => 1,
                'b' => 2
            )
        );
        $p3 = array(
            'x' => 0,
            array(
                'a' => 1,
                'b' => 2
            )
        );
        $p4 = array(
            array(
                'x' => 0
            ),
            array(
                'OR' => array(
                    'a' => 1,
                    'b' => 2
                ),
                array(
                    'c' => 3
                )
            )
        );
        // Params set selection
        $p = $p4;
        xUtil::pre($p);
        // Class test
        $c = xSqlWhereInterpreter::load(null, $p);
        xUtil::pre($c->test());
        xUtil::pre($c->structure());
        die();
    }
}

class xSqlWherePredicateGroup {

    public $predicates = array();

    /**
     * Default operator.
     * @var string
     */
    public $operator = 'AND';

    /**
     * Accepted operators.
     * @var array
     */
    public static $operators = array('AND', 'OR');

    function __construct($predicates=array(), $operator=null) {
        $this->predicates = xUtil::arrize($predicates);
        if ($operator) $this->operator = $operator;
    }

    function __toString() {
        return "(".implode(" $this->operator ", $this->predicates).")";
    }
}

class xSqlWherePredicate {

    public $field;
    public $value;

    /**
     * Default operator.
     * @var string
     */
    public $comparator = '=';

    /**
     * Accepted comparators.
     * @var array
     */
    public static $comparators = array('=', '!=', '<', '>', '<=', '>=', 'LIKE', 'IN', 'BETWEEN');

    function __construct($field, $value, $comparator=null) {
        $this->field = $field;
        $this->value = $value;
        if ($comparator) $this->comparator = $comparator;
    }

    function __toString() {
        return "{$this->field} {$this->comparator} {$this->value}";
    }
}

class xSqlWhereInterpreter extends xPlugin {

    /**
     * Recursively processes parameter items,
     * extracts structure
     * @param array Base of the where structure to be returned (for recursive calls).
     * @return array A where structure.
     */
    protected function walk_item($p) {
        $structure = array();
        foreach ($p as $key => $value) {
            if ($this->is_group($key)) {
                // Computes operator, or null if no operator defined
                $operator = array_shift(array_intersect(xSqlWherePredicateGroup::$operators, array($key)));
                // Creates predicates array through iteration
                $predicates = $this->walk_item($value);
                // Adds predicates to group
                $structure[] = new xSqlWherePredicateGroup($predicates, $operator);
            } else {
                $structure[] = new xSqlWherePredicate($key, $value);
            }
        }
        return $structure;
    }

    /**
     * Determines whether the $key implies a group or a field.
     * @param mixed Key to be tested
     */
    protected function is_group($key) {
        // max() is used to simulate an OR operator
        return max(
            // is $key an operator?
            in_array(strtoupper($key), xSqlWherePredicateGroup::$operators),
            // is $key an integer index?
            (int)$key === $key
        );
    }

    function structure() {
        // Ensures that top-level array is a 'group'
        $p = $this->params;
        if (count($p)) $p = array($p);
        // Recursively creates a predicates structure
        return $this->walk_item($p);
    }

    function test() {
        $s = $this->structure();
        return implode(' ', $s);
    }

    public static function load($name, $params=null) {
        return new xSqlWhereInterpreter($params);
    }

}