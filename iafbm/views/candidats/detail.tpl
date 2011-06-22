<div id="target"></div>


<script type="text/javascript">

Ext.onReady(function() {

    Ext.QuickTips.init();

    var formPanel = Ext.create('iafbm.form.Candidat', {
        record: {
            model: iafbm.model.Candidat,
            id: <?php echo $d['id'] ?>
        },
        renderTo: 'target'
    });

});

</script>