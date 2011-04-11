<?php foreach ($d['messages'] as $message): ?>
<div class="<?php echo $message['type'] ?> message"><?php echo $message['text'] ?></div>
<?php endforeach ?>
