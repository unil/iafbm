<img src="<?php echo u('a/img/controllers/feed/events/user_delete.png') ?>"/>

<a href="<?php echo u("personnes/{$d['entity']['id']}") ?>">
  <?php echo "{$d['entity']['prenom']} {$d['entity']['nom']}" ?>
</a>

supprimé

(par <?php echo $d['version']['creator'] ?>)