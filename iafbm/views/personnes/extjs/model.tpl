Ext.define('Personne', {
    extend: 'Ext.data.Model',
    fields: <?php echo xView::load('personnes/extjs/fields')->render() ?>,
    validations: []
});