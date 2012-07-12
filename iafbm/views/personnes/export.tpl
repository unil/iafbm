<?php
    $fields_labels = xController::load('personnes')->export_fields_labels;
    $fields_checked = array(
        'nom', 'prenom'
    );
?>

<h1>Export des personnes &amp; adresses</h1>

<div>
  <form method="post" style="padding:10px;line-height:166%">
  <h2>Sélectionnez votre système</h2>
<?php foreach ($d['modes'] as $mode => $x): ?>
<?php
    $id = "mode-{$mode}";
    $checked = ($mode==array_shift(array_keys($d['modes']))) ? ' checked="checked"' : null;
?>
    <div>
      <input type="radio" name="mode" id="<?php echo $id ?>" value="<?php echo $mode ?>" <?php echo $checked ?>/>
      <label for="<?php echo $id ?>"><?php echo $mode ?></label>
    </div>
<?php endforeach ?>
  <br/>
  <h2>Sélectionnez les champs à exporter:</h2>
<?php foreach ($fields_labels as $field => $label): ?>
<?php
    $id = "export-fields-{$field}";
    $checked = in_array($field, $fields_checked) ? ' checked="checked"' : null;
?>
    <div id="fields-list">
      <input type="checkbox" id="<?php echo $id ?>" name="fields[]" value="<?php echo $field ?>"<?php echo $checked ?>/>
      <label for="<?php echo $id ?>"><?php echo $label ?></label>
    </div>
<?php endforeach ?>
    <br/>
    <a href="javascript:void(0)" id="select-all-fields">Sélectionner tous les champs</a>
    <br/><br/>
    <input type="submit" id="do-export" value="Télécharger le fichier" style="padding:10px"/>
  </form>
</div>

<script>
// Select all fields feature
Ext.fly('select-all-fields').on('click', function() {
    Ext.select('#fields-list input').each(function(el) {
        el.dom.checked="checked"
    });
});
// Shows spinner on form submit
Ext.fly('do-export').on('click', function() {
    this.findParent('form').submit();
    this.replaceWith({
        tag: 'div',
        style: 'font-weight:bold',
        children: [{
            tag: 'img',
            style: 'padding: 25px',
            src: '<?php echo u('a/img/icons/spinner-32x32.gif') ?>',
        }, {
            tag: 'span',
            style: 'position:relative; left:5px; bottom:12px',
            html: 'Patientez pendant la préparation des données'
        }]
    })
});
</script>