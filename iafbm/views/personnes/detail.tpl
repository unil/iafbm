<div id="target"></div>


<script type="text/javascript">

Ext.onReady(function() {

    var formPanel = Ext.create('iafbm.form.Personne', {
        fetch: {
            model: iafbm.model.Personne,
            id: <?php echo $d['id'] ?>
        },
        renderTo: 'target'
    });

});

</script>