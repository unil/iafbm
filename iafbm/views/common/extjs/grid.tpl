<div id="editor-grid"></div>

<script type="text/javascript">

Ext.onReady(function(){

    Ext.QuickTips.init();

    new Ext.ia.grid.EditPanel({
        id: '<?php echo $d['id'] ?>',
        renderTo: 'editor-grid',
        frame: true,
        title: 'Test',
        width: 880,
        height: 330,
        store: new iafbm.store.<?php echo $d['model'] ?>(),
        columns: iafbm.columns.<?php echo $d['model'] ?>,
        pageSize: <?php echo $d['pageSize'] ?>
    });
});

</script>