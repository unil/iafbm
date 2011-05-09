Ext.define('Personne', {
    extend: 'Ext.data.Model',
    fields: <?php echo xView::load('personnes/extjs/fields')->render() ?>,
    validations: [],
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
    }
});