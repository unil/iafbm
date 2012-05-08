<img src="<?php echo u('a/img/controllers/feed/events/delete.png') ?>"/>

<a href="<?php echo u("personnes/{$d['entity']['personne_id']}") ?>">
  <?php echo "{$d['entity']['personne_prenom']} {$d['entity']['personne_nom']}" ?>
</a>

supprimé de la

<a href="<?php echo u("commissions/{$d['entity']['commission_id']}") ?>">
  commission n°<?php echo $d['entity']['commission_id'] ?>
</a>