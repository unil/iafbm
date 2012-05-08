<img src="<?php echo u('a/img/controllers/feed/events/pencil.png') ?>"/>

<a href="<?php echo u("personnes/{$d['entity']['personne_id']}") ?>">
  <?php echo "{$d['entity']['personne_prenom']} {$d['entity']['personne_nom']}" ?>
</a>

modifié dans la

<a href="<?php echo u("commissions/{$d['entity']['commission_id']}") ?>">
  commission n°<?php echo $d['entity']['commission_id'] ?>
</a>

par

<?php echo $d['version']['creator'] ?>