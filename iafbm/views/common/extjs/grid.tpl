<h1><?php echo $d['title'] ?></h1>

<div id="editor-grid"></div>

<script type="text/javascript">

Ext.onReady(function(){
    
    <?php if (isset($d['filters'])): ?>
    var filters = Ext.createWidget('ia-combofilter', {
        gridId: '<?php echo $d['id'] ?>',
        renderTo: 'editor-grid',
        title: 'Filtres',
        filters: <?php echo json_encode($d['filters'])?>
    });
    <?php endif ?>

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
            toolbarButtons: <?php echo json_encode($d['toolbarButtons']) ?>,
        <?php endif ?>
        
        <?php if (isset($d['makeData'])): ?>
            makeData: function(record) {
                toto = record;
                return {
                <?php
                    foreach($d['makeData']['keyValue'] as $newStoreField => $existStoreField){
                        printf("%s: record.get('%s'),", $newStoreField, $existStoreField);
                    }
                    foreach($d['makeData']['value'] as $newStoreField => $value){
                        printf("%s: %s,", $newStoreField, $value);
                    }
                ?>
                }
            },
        <?php endif ?>
        
    });
});

</script>