<div class="i18n"><?php $langs = xContext::$config->i18n->lang->alias->toArray() ?>
  <?php foreach ($langs as $lang => $locale ): ?><a <?php if ($lang == xContext::$lang): ?>class="selected"<?php endif ?> href="?xlang=<?php echo $lang ?>"><?php echo $lang ?></a><?php if (array_pop(array_keys($langs))!=$lang): ?>|<?php endif ?><?php endforeach; ?>
</div>
