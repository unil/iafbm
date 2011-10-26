<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <title>UNIL - FBM - Intranet Administratif</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="icon" href="https://wwwfbm.unil.ch/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="https://wwwfbm.unil.ch/favicon.ico" type="image/x-icon" />
    <?php foreach (isset($m['css']) ? $m['css'] : array() as $css): ?>
      <link rel="stylesheet" href="<?php echo $css ?>" type="text/css"/>
    <?php endforeach ?>
    <?php foreach (isset($m['js']) ? $m['js'] : array() as $js): ?>
      <script type="text/javascript" src="<?php echo $js ?>"></script>
    <?php endforeach ?>
  </head>
  <body>
    <div style="text-align: center; font-weight: bold;">
      Suivre le développement en cliquant <a href="https://github.com/unil/iafbm/issues?milestone=4&state=open" target="_blank"> ici</a>
    </div>
    <div id="page">
      <div id="header">
        <p>FBM, Intranet Administratif</p>
      </div>
      <div id="content">
        <div id="sidebar">
          <div class="box">
            <div><?php echo xView::load('layout/navigation')->render() ?></div>
          </div>
        </div>
        <div id="main">
          <?php echo xView::load('layout/messages')->render() ?>
          <?php echo $d['html']['content'] ?>
        </div>
      </div>
      <div style="clear:both"></div>
      <div id="footer">Footer</div>
    </div>
  </body>
</html>