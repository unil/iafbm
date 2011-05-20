<?php
$items = array(
    array(
        'label' => _('About us'),
        'link' => u('about/us'),
        //'img' => u('a/img/views/footer/about.png')
    )
);
?>

<div id="footer">
  <div class="footer-links">
<?php foreach($items as $item): ?>
    <a href="<?php echo $item['link'] ?>">
      <?php if ($item['img']): ?><img src="<?php echo $item['img'] ?>"/><?php endif ?>
    </a>
    <a style="vertical-align:5px;margin-right:20px" href="<?php echo $item['link'] ?>"><?php echo $item['label'] ;?></a>
<?php endforeach ?>
  </div>
  <div class="footer-legal">
    &copy; UNIL, Faculté de Biologie et Medecine, tous droits réservés.
  </div>
  <div style="clear:both"></div>
</div>
