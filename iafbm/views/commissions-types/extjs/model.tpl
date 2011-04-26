Ext.define('CommissionType', {
    extend: 'Ext.data.Model',
    fields: <?php echo xView::load('commissions-types/extjs/fields')->render() ?>,
    validations: []
});