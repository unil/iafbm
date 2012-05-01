<style>
tr.president {
    font-weight: bold;
}
</style>

<h1>Composition de la commission pour <?php echo $d['commission']['nom'] ?></h1>
<hr/>
<table class="noborder">
<?php foreach($d['membres'] as $membre): ?>
<?php
    // Retrieves personne._activite pseudo-field
    $personne = xController::load('personnes', array('id' => $membre['id']))->get();
    $activite = @$personne['items'][0]['_activites'];
    // Determines if personne is 'president'
    $class = ($membre['commission_fonction_id'] == 1) ? 'president' : null;

?>
  <tr class="<?php echo $class ?>">
    <td>
        <?php echo "{$membre['personne_denomination_nom']}" ?>
    </td>
    <td>
        <?php echo "{$membre['personne_prenom']} {$membre['personne_nom']}" ?>
    </td>
    <td>
        <?php echo $membre['commission_fonction_nom'] ?>
        <?php if ($membre['fonction_complement']) echo "({$membre['fonction_complement']})"  ?>
    </td>
    <td>
        <?php echo $activite ?>
    </td>
  </tr>
<?php endforeach; ?>
</table>
<hr/>
<div style="font-size:8pt">
  Décanat/Unité Relève/Ref. <?php echo $d['commission']['id'] ?>
</div>