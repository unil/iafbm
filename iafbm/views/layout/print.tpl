<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>UNIL - FBM - Intranet Administratif</title>
    <?php foreach (isset($m['css']) ? $m['css'] : array() as $css): ?>
      <!-- <link rel="stylesheet" href="<?php echo $css ?>" type="text/css"/> -->
      <style><?php echo file_get_contents($css) ?></style>
    <?php endforeach ?>
  </head>
  <body>
    <div id="header">
      <img src="<?php echo u('a/img/id/unil-print.png', true) ?>" alt="Université de Lausanne"/>
      <div class="tagline">
          Faculté de biologie
          <br/>
          et de médecine
      </div>
    </div>
    <?php echo $d['content'] ?>
  </body>
</html>