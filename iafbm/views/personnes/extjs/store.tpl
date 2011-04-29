new Ext.data.Store({
    model: 'Personne',
    proxy: {
        type: 'rest',
        url : '<?php echo u('api/personnes') ?>',
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
    autoLoad: <?php echo isset($d['autoload']) ? $d['autoload'] : 'false'; ?>,
    autoSync: <?php echo isset($d['autoload']) ? $d['autoload'] : 'false'; ?>
})