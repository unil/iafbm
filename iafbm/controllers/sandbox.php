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
        // Params set selection
        $p = $p4;
        xUtil::pre($p);
        // Class test
        $parser = new xSqlWhereParser($p);
        xUtil::pre($parser->test());
        xUtil::pre($parser->parse());
        die();
    }
}

class xSqlSelect {} // For values: enquoting, etc
class xSqlFrom {}
class xSqlJoin {}
class xSqlOrder {}
class xSqlGroup {}
class xSqlOffset {}
class xSqlExpression {}

class xSqlWhere {

    public $component;

    function __construct(xSqlWherePredicateGroup $component) {
        $this->component = $component;
    }

    function __toString() {
        return implode(' ', array("WHERE", $this->component));
    }
}

/**
 * Anatomically, a predicate group contains a set of predicates and an operator.
 */
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

    function __construct(array $predicates=array(), $operator=null) {
        $this->predicates = xUtil::arrize($predicates);
        if ($operator) $this->operator = $operator;
    }

    function __toString() {
        $operator = " {$this->operator} ";
        return implode(array("(", implode($operator, $this->predicates), ")"));
    }
}

/**
 * Anatomically, a predicate contains a field/value pair and a comparator.
 */
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

/**
 * Creates a xSqlWhere clause from a structured description array.
 *
 * Example 1 would generate: ((a = 1 AND b = 2 AND (c = 3))
 * REST forms (FYI):
 * - HTTP: ?w[a]=1&w[b]=2&[c]=3 or ?a=1&b=2&c=3
 * - JSON: { a:1, b:2, c:3 }
 * <code>
 * array(
 *     'a' => 1,
 *     'b' => 2,
 *     'c' => 3
 * );
 * </code>
 *
 * Example 2 would generate: ((x = 0) AND ((a = 1 OR b = 2) AND (c = 3)))
 * REST forms (FYI):
 * - HTTP: ?w[0][x]=0&w[1][OR][a]=1&w[1][OR][b]=2&w[1][][c]=3
 * - JSON: [ {x:0}, { OR:{a:1, b:2}, {c:3} } ]
 * <code>
 * array(
 *     array(
 *         'x' => 0
 *     ),
 *     array(
 *         'OR' => array(
 *             'a' => 1,
 *             'b' => 2
 *         ),
 *         array(
 *             'c' => 3
 *         )
 *     )
 * )
 * </code>
 */
class xSqlWhereParser extends xPlugin {

    // Dev purpose:
    // Makes constructor public for easy instanciation (testing)
    // TODO: remove this
    public function __construct(array $params=array()) {
        return parent::__construct($params);
    }

    /**
     * Recursively processes parameter items,
     * extracts structure
     * @param array Base of the where structure to be returned (for recursive calls).
     * @return array A where structure.
     */
    protected function walk_item(array $p) {
        $structure = array();
        // Processes predicates/groups items
        foreach ($p as $key => $item) {
            // Determines wheter to process item:
            // - as a group: which contains other predicates and/or groups
            // - as a predicate: which contains a field:value pair
            if ($this->is_group($key)) {
                // Computes operator, or null if no operator defined
                $operator = array_shift(array_intersect(
                    xSqlWherePredicateGroup::$operators,
                    array($key)
                ));
                // Recurses into group childrens to create predicates array structure
                $predicates = $this->walk_item($item);
                // Adds predicates structure to group
                $structure[] = new xSqlWherePredicateGroup($predicates, $operator);
            } else /* $item is a predicate */ {
                // Parses comparator
                // TODO: Setup 'comparator' concept and parse comparator
                $comparator = null;
                // In this case, $key:$item is the predicate field:value pair
                $field = $key;
                // Adds single predicate to group
                $structure[] = new xSqlWherePredicate($field, $item, $comparator);
            }
        }
        return $structure;
    }

    /**
     * Determines whether the $key implies a group or a predicate.
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

    /**
     * @return xSqlWhere Containing the parsed where structure
     */
    function parse() {
        // Ensures that top-level array is a 'group'
        $p = $this->params;
        if (count($p)) $p = array($p);
        // Recursively creates a predicates structure
        $groups = $this->walk_item($p);
        // Extracts the top-level group object (xSqlWherePredicateGroup)
        $group = array_shift($groups);
        //
        return new xSqlWhere($group);
    }

    function test() {
        return (string)$this->parse();
    }
}