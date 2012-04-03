<?php
    $president = array();
    foreach ($d['membres'] as $i => $membre) {
        if ($membre['commission-fonction_id'] == 1) {
          $president = $membre;
          unset($d['membres'][$i]);
        }
    }
?>

<h1><?php echo $d['commission']['nom'] ?></h1>
<hr/>
<table class="noborder">
  <tr>
    <th style="width:50%"><?php echo "[Titre] {$president['personne_prenom']} {$president['personne_nom']}" ?></th>
    <th style="width:50%"><?php echo $president['commission-fonction_nom'] ?></th>
  </tr>
<?php foreach($d['membres'] as $membre): ?>
  <tr>
    <td><?php echo "[Titre] {$membre['personne_prenom']} {$membre['personne_nom']}" ?></td>
    <td><?php echo $membre['commission-fonction_nom'] ?></td>
  </tr>
<?php endforeach; ?>
</table>
<hr/>
<div style="font-size:8pt">
  No de la commission: <?php echo $d['commission']['id'] ?>
</div>