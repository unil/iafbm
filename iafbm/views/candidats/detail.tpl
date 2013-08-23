<h1>Détail candidat</h1>
<div id="target"></div>


<script type="text/javascript">

Ext.onReady(function() {

    var formPanel = Ext.create('iafbm.form.Candidat', {
        fetch: {
            model: iafbm.model.Candidat,
            id: <?php echo $d['id'] ?>
        },
        renderTo: 'target'
    });

});

</script>