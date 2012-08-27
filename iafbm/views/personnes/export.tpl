<?php
    // Default checked fields
    $fields_labels = xController::load('personnes')->export_fields_labels;
    $fields_checked = array(
        'nom',
        'prenom',
        'personne_denomination_nom',
        'adresse_adresse_type_nom',
        'adresse_rue',
        'adresse_npa',
        'adresse_lieu',
        'adresse_pays_nom',
        'personne_email_adresse_type_nom',
        'personne_email_email'
    );
    // Default operating system (detection, or defaults to modes 1st option)
    $agent = $_SERVER['HTTP_USER_AGENT'];
    if(preg_match('/Linux/', $agent)) $os = 'linux';
    elseif(preg_match('/Win/', $agent)) $os = 'windows';
    elseif(preg_match('/Mac/', $agent)) $os = 'mac';
    else $os = 'Unknown';
    $mode_detected = in_array($os, array_keys($d['modes'])) ?
        $os : array_shift(array_keys($d['modes']));
?>

<h1>Exportation des personnes (CSV)</h1>

<div>
  <form id="export-form" method="post" style="padding:10px;line-height:166%">
  <h2>Sélectionnez votre système</h2>
<?php foreach ($d['modes'] as $mode => $x): ?>
<?php
    $id = "mode-{$mode}";
    $checked = ($mode_detected==$mode) ? ' checked="checked"' : null;
?>
    <div>
      <input type="radio" name="mode" id="<?php echo $id ?>" value="<?php echo $mode ?>" <?php echo $checked ?>/>
      <label for="<?php echo $id ?>"><?php echo ucwords($mode) ?></label>
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
Ext.get('select-all-fields').on('click', function() {
    Ext.select('#fields-list input').each(function(el) {
        el.dom.checked="checked"
    });
});
// Shows spinner on form submit
Ext.get('do-export').on('mouseup', function() {
    Ext.get('export-form').dom.submit();
    Ext.get('do-export').replaceWith({
        tag: 'div',
        style: 'font-weight:bold',
        children: [{
            tag: 'img',
            src: '<?php echo u('a/img/icons/spinner-32x32.gif') ?>',
        }, {
            tag: 'span',
            style: 'position:relative; left:15px; bottom:12px',
            html: 'Patientez pendant la préparation des données'
        }]
    })
});
</script>