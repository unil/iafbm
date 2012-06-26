<style>
table {
    line-height: 2em;
}
tr {
    vertical-align: top;
}
tr.president {
    font-weight: bold;
}
</style>

<h1>Composition de la commission pour <?php echo $d['commission']['nom'] ?></h1>
<hr/>
<table class="noborder" style="vertical-align:top">
<?php foreach($d['membres'] as $membre): ?>
<?php
    // Retrieves personne._activite pseudo-field
    $personne = xController::load('personnes', array('id' => $membre['personne_id']))->get();
    $activite = @$personne['items'][0]['_activites'];
    // Determines if personne is 'president'
    $class = ($membre['commission_fonction_id'] == 1) ? 'president' : null;
?>
  <tr class="<?php echo $class ?>">
    <td style="width:15%">
        <?php echo implode('<br/>', $membre['personne_denomination_abreviation']) ?>
    </td>
    <td style="width:30%">
        <?php echo "{$membre['personne_prenom']} {$membre['personne_nom']}" ?>
    </td>
    <td style="width:35%">
        <?php $count = count($membre['commission_fonction_nom']) ?>
        <?php for($i=0; $i<$count; $i++): ?>
            <?php $fonction =  $membre['commission_fonction_nom'][$i] ?>
            <?php $complement =  $membre['fonction_complement'][$i] ?>
            <?php echo $fonction ?>
            <?php if ($complement) echo "({$complement})"  ?>
            <?php if ($i < $count-1) echo '<br/>'; ?>
        <?php endfor ?>
    </td>
    <td style="width:20%">
        <?php echo $activite ?>
    </td>
  </tr>
<?php endforeach; ?>
</table>
<hr/>
<div style="font-size:8pt">
  Décanat/Unité Relève/Réf. <?php echo $d['commission']['id'] ?>
</div>