<?php
    $fields_labels = xController::load('personnes')->export_fields_labels;
    $fields_checked = array(
        'nom', 'prenom'
    );
?>

<h1>Export des personnes &amp; adresses</h1>

<form method="post" style="padding:10px">
<?php foreach ($fields_labels as $field => $label): ?>
<?php
    $id = "export-fields-{$field}";
    $checked = in_array($field, $fields_checked) ? ' checked="checked"' : null;
?>
  <div>
    <input type="checkbox" id="<?php echo $id ?>" name="fields" value="<?php echo $label ?>"<?php echo $checked ?>/>
    <label for="<?php echo $id ?>"><?php echo $label ?></label>
  </div>
<?php endforeach ?>
  <br/>
  <input type="submit" id="do-export" value="Télécharger le fichier" style="padding:10px"/>
</form>

<script>
Ext.fly('do-export').on('click', function() {
    this.findParent('form').submit();
    this.replaceWith({
        tag: 'div',
        style: 'font-weight:bold',
        children: [{
            tag: 'img',
            src: '<?php echo u('a/img/icons/spinner-32x32.gif') ?>',
        }, {
            tag: 'span',
            style: 'position:relative; left:5px; bottom:12px',
            html: 'Patientez pendant la préparation des données'
        }]
    })
});
</script>