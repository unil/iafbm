Ext.define('Pays', {
    extend: 'Ext.data.Model',
    fields: <?php echo xView::load('pays/extjs/fields')->render() ?>,
    validations: []
});