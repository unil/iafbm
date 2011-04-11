Okikoo error report from user: <?php echo $d['items']['username'] ? $d['items']['username'] : '[user not logged in]' ?>

Date: <?php echo xUtil::date() . ' at ' . xUtil::time() ?>

URL history: <?php foreach ($d['items']['history'] as $i => $url): ?>
<?php echo "#{$i} {$url}\n" ?>
<?php endforeach ?>

Error message: <?php echo $d['items']['exception']->getMessage() ?>

<?php echo $d['items']['exception'] ?>