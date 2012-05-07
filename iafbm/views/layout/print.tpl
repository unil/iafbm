<?php

// Returns a file:// url from a http(s):// url
function pdfurl($url) {
    $is_pdf = !isset($_REQUEST['html']);
    if (!$is_pdf) return $url;
    $file = preg_replace('/.*a(.+)/', 'file://'.xContext::$basepath.'/public/a$1', $url);
    return $file;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>UNIL - FBM - Intranet Administratif</title>
    <?php foreach (isset($m['css']) ? $m['css'] : array() as $css): ?>
      <link rel="stylesheet" href="<?php echo pdfurl($css) ?>" type="text/css"/>
    <?php endforeach ?>
  </head>
  <body>
    <div id="header">
      <img src="<?php echo pdfurl(u('a/img/id/unil-print.png', true)) ?>" alt="Université de Lausanne"/>
      <div class="tagline">
          Faculté de biologie
          <br/>
          et de médecine
      </div>
    </div>
    <?php echo $d['content'] ?>
  </body>
</html>