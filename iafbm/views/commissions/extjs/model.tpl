Ext.define('Commission', {
    extend: 'Ext.data.Model',
    fields: <?php echo xView::load('commissions/extjs/fields')->render() ?>,
    validations: []
});