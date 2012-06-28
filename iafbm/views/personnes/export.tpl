<?php
    // TODO: PersonnesController to feed $fields
    $fields = array(
        'nom' => array(
            'label' => 'Nom',
            'checked' => true
        ),
        'prenom' => array(
            'label' => 'Prénom',
            'checked' => false
        )
    )
?>

<h1>Export des personnes &amp; adresses</h1>

<form method="post">
<?php foreach ($fields as $fieldname => $field): ?>
<?php
    $id = "export-fields-{$fieldname}";
    $label = $field['label'];
    $checked = $field['checked'] ? ' checked="checked"' : null;
?>
  <div>
    <input type="checkbox" id="<?php echo $id ?>" name="fields" value="<?php echo $fieldname ?>"<?php echo $checked ?>/>
    <label for="<?php echo $id ?>"><?php echo $label ?></label>
  </div>
<?php endforeach ?>
  <input type="submit" value="Télécharger le fichier"/>
</form>