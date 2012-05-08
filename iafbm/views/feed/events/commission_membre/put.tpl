<img src="<?php echo u('a/img/controllers/feed/events/add.png') ?>"/>

<a href="<?php echo u("personnes/{$d['entity']['personne_id']}") ?>">
  <?php echo "{$d['entity']['personne_prenom']} {$d['entity']['personne_nom']}" ?>
</a>

ajouté à la

<a href="<?php echo u("commissions/{$d['entity']['commission_id']}") ?>">
  commission n°<?php echo $d['entity']['commission_id'] ?>
</a>

en tant que

<a href="#">
  <?php echo strtolower($d['entity']['commission_fonction_nom']) ?>
</a>