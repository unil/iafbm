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
}