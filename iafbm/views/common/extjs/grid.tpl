<h1><?php echo $d['title'] ?></h1>

<div id="editor-grid"></div>

<script type="text/javascript">

Ext.onReady(function(){

    ep = new Ext.ia.grid.EditPanel({
        id: '<?php echo $d['id'] ?>',
        renderTo: 'editor-grid',
        frame: false,
        width: 936,
        height: <?php echo $d['height'] ?>,
        store: new iafbm.store.<?php echo $d['model'] ?>({
            params: <?php echo json_encode($d['store-params']) ?>
        }),
        columns: <?php echo $d['columns'] ?>,
        pageSize: <?php echo $d['pageSize'] ?>,
        editable: <?php echo json_encode($d['editable']) ?>,
        autoSync: <?php echo json_encode($d['autoSync']) ?>,
        <?php if (isset($d['toolbarButtons'])): ?>
        toolbarButtons: <?php echo json_encode($d['toolbarButtons']) ?>
        <?php endif ?>
    });
});

</script>