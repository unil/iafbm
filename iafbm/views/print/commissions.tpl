<h1 class="table-header">Liste des commissions actives</h1>
<table>
  <tr>
    <th>Type</th>
    <th>Nom</th>
    <th>Section</th>
    <th>Président</th>
    <th>Etat</th>
  </tr>
<?php foreach($d as $item): ?>
  <tr>
    <td><?php echo $item['commission-type_racine'] ?></td>
    <td><?php echo $item['nom'] ?></td>
    <td><?php echo $item['section_nom'] ?></td>
    <td><?php echo $item['_president'] ?></td>
    <td><?php echo $item['commission-etat_nom'] ?></td>
  </tr>
<?php endforeach; ?>
</table>