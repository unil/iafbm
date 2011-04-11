<!doctype html>
<html>
  <head>
    <title>
      <?php echo $m['title'] ?>
    </title>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
    <script type="text/javascript" src="<?php echo xContext::$baseuri.'/a/js/view/context.js' ?>"></script>
<?php foreach (isset($m['js']) ? $m['js'] : array() as $js): ?>
    <script type="text/javascript" src="<?php echo $js ?>"></script>
<?php endforeach ?>
<?php foreach (isset($m['css']) ? $m['css'] : array() as $css): ?>
    <link rel="stylesheet" href="<?php echo $css ?>" type="text/css"/>
<?php endforeach ?>
  </head>
  <body>
    <div id="wrapper">
      <div id="header">
        <?php //echo xView::load('layout/i18n')->render(); ?>
        <?php //echo xView::load('member/authline')->render(); ?>
        <a href="<?php echo u('') ?>"><img src="<?php echo xContext::$baseuri ?>/a/img/id/header-logo.png" alt="Example site"/></a>
      </div>
      <div style="clear:both"></div>
      <hr/>

      <?php //echo xView::load('layout/navigation', $m['navigation'])->render(); ?>

      <?php if ($m['subnavigation']['html']): ?>
        <div id="subnavigation">
          <?php echo $m['subnavigation']['html'] ?>
        </div>
      <?php endif ?>

      <div id="content" class="<?php echo $m['layout']['type'] ?>">
        <?php echo xView::load('layout/messages')->render() ?>
        <?php echo $d['html']['content'] ?>
      </div>
<?php if (!empty($m['related']['additional'])): ?>
      <div id="additional" class="<?php echo $m['layout']['type'] ?>">
        <?php echo $m['related']['additional'] ?>
      </div>
<?php endif ?>

      <div style="clear:both"></div>

      <?php echo xView::load('layout/footer')->render(); ?>

    </div>

<?php if (xContext::$auth->is_role('developer')): ?>
    <div>
      <hr/>
      <?php
          require_once(xContext::$basepath."/lib/xfreemwork/lib/Reusables/DeveloperInformation/DeveloperInformation.php");
          $devinfo = new xDeveloperInformation();
          echo $devinfo->render();
      ?>
      <hr/>
    </div>
<?php endif ?>

    <?php // echo xView::load('all/google.analytics')->render(); ?>
  </body>
</html>
