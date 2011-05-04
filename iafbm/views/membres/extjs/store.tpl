new Ext.data.Store({
    model: 'Membre',
    proxy: {
        type: 'rest',
        url : '<?php echo u('api/membres') ?>',
        limitParam: 'xlimit',
        startParam: 'xoffset',
        pageParam: undefined,
        reader: {
            type: 'json',
            root: 'items',
            totalProperty: 'xcount'
        },
        writer: {
            root: 'items'
        }
    },
    pageSize: <?php echo isset($d['pagesize']) ? $d['pagesize'] : 'null'; ?>,
    autoLoad: <?php echo isset($d['autoload']) ? var_export((bool)$d['autoload']) : 'true'; ?>,
    autoSync: <?php echo isset($d['autosync']) ? var_export((bool)$d['autosync']) : 'true'; ?>
})