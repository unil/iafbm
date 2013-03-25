<h1><?php echo $d['commission']['nom'] ?></h1>
<hr/>
<h1 class="table-header">Candidats</h1>
<table class="noborder">
  <tr>
    <th>Nom et prénom</th>
    <th>Date de naissance</th>
    <th>Pays d'origine</th>
    <th>Formation supérieure et lieu</th>
    <th>Positions actuelle et lieu</th>
  </tr>
<?php foreach($d['candidats'] as $candidat): ?>
  <tr>
    <td><?php echo "{$candidat['prenom']} {$candidat['nom']}" ?></td>
    <td><?php echo $candidat['date_naissance'] ?></td>
    <td><?php echo $candidat['date_naissance'] ?></td>
    <td><?php echo $candidat[''] ?></td>
    <td><?php echo "{$candidat['position_actuelle_fonction']} {$candidat['position_actuelle_lieu']}" ?></td>

  </tr>
<?php endforeach; ?>
</table>
<hr/>
<div style="font-size:8pt">
  No de la commission: <?php echo $d['commission']['id'] ?>
</div>