Ext.define('Membre', {
    extend: 'Ext.data.Model',
    fields: <?php echo xView::load('membres/extjs/fields')->render() ?>,
    validations: []
});