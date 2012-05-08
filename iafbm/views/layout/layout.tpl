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
        <?php foreach (isset($m['js']) ? $m['js'] : array() as $js): ?>
      <script type="text/javascript" src="<?php echo $js ?>"></script>
    <?php endforeach ?>
    <?php foreach (isset($m['css']) ? $m['css'] : array() as $css): ?>
      <link rel="stylesheet" href="<?php echo $css ?>" type="text/css"/>
    <?php endforeach ?>

  </head>
  <body>
    <div style="text-align: center; font-weight: bold;">
      <a href="https://wwwfbm.unil.ch/wiki/iafbm/" target="_blank">
          Suivre l'avancement en allant sur le wiki du projet
      </a>
    </div>
<?php if (xContext::$profile == 'development'): ?>
    <div class="warning message" style="text-align:center;font-weight:bold">
        Please mind: You are in development mode and security is disabled
    </div>
<?php endif ?>
	<!-- BEGIN PAGE -->
	<div id="page">
		<!-- BEBIN HEADER -->
		<div id="header">
			 <div id="bar">Intranet Administratif</div>
		</div>
		<!-- END HEADER -->
		<!-- BEGIN CONTENT -->
		<div id="content">
			<!-- BEGIN SIDEBAR -->
			<div id="sidebar">
				<?php echo xView::load('layout/navigation')->render() ?>
			</div>
			<!-- END SIDEBAR -->
			<!-- BEGIN MAIN -->
			<div id="main">
				<!-- BEGIN INTRANET -->
				<?php echo xView::load('layout/messages')->render() ?>
				<div class="box">
	          	<?php echo $d['html']['content'] ?>
	          	</div>
				<!-- END INTRANET -->
			</div>
			<!-- END MAIN -->
		</div>
		<!-- END CONTENT -->
		<!-- BEGIN FOOTER -->
		<div id="footer"><?php echo xView::load('layout/footer')->render() ?></div>
		<!-- END FOOTER -->
	</div>
	<!-- END PAGE -->
	</body>
</html>