<!doctype html>
<html>
  <head>
    <title>
      <?php echo $m['title'] ?>
    </title>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<?php foreach (isset($m['css']) ? $m['css'] : array() as $css): ?>
    <link rel="stylesheet" href="<?php echo $css ?>" type="text/css"/>
<?php endforeach ?>
<?php foreach (isset($m['js']) ? $m['js'] : array() as $js): ?>
    <script type="text/javascript" src="<?php echo $js ?>"></script>
<?php endforeach ?>
  </head>
  <body>
    <div align="left">
    <table class="page" border="0" cellpadding="0" cellspacing="0" width="1050">
        <tbody>
        <tr>
            <td colspan="2"><table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                <tr>
                    <td class="header"><img src="https://wwwfbm.unil.ch/html/img/head_fbm.gif" alt="Barre verticale" border="0" /></td>
                </tr>
                </tbody>
            </table></td>
        </tr>
        <tr>
            <td height="10" colspan="2" class="header_title"><table border="0" width="100%">
                <tr>
                <td bgcolor="e88c13"><strong>Intranet administratif de la FBM - version de développement</strong></td>
                <td>&nbsp;</td>
                <td valign="bottom" align="right">2011.05.24 14:00 - Utilisateur: smeier6 (Stefan Meier)</td>
                </tr>
            </table></td>
        </tr>
        <tr>
            <td height="2" bgcolor="#f5e3c6">&nbsp;</td>
            <td height="2" bgcolor="#f5e3c6">&nbsp;</td>
        </tr>
        <tr bgcolor="#f5e3c6">
            <td width="180" rowspan="2" valign="top" bgcolor="#EEEEEE" class="navigation"><table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                <tr>
                    <!-- Begin Menu -->
                    <td height="80" valign="top">
                      <div id="navigation">
                        <?php echo xView::load('layout/navigation')->render() ?>
                      </div>
                    </td>
                    <!-- End Menu -->
                </tr>
                </tbody>
            </table></td>
            <td width="620" align="center" valign="top" bgcolor="#FFFFFF"><table border="0" cellpadding="0" cellspacing="0" width="880">
                <tbody>
                <tr>
                    <td height="100%" bgcolor="#f5e3c6"><table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tbody>
                        <tr>
                            <td align="left" style="padding-left: 6px;"><!-- DEBUT ONGLETS -->

                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                <td valign='top' width='80'>&nbsp;</td>
                                <td valign='top' width='80'>&nbsp;</td>
                                </tr>
                            </table>

                            <!-- FIN ONGLETS --></td>
                        </tr>
                        <tr>
                            <td class="text">
                            <!-- Content -->
<?php echo xView::load('layout/messages')->render() ?>
<?php echo $d['html']['content'] ?>
                            <!-- End Content -->
                            </td>
                        </tr>
                        </tbody>
                    </table></td>
                </tr>
                </tbody>
            </table></td>
        </tr>
        <tr>
            <td height="20" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
        </tr>
        <!-- Begin Footer -->
        <tr>
            <td class="footer" height="20" colspan="2"><table border="0" width="100%">
                <tr>
                <td><img src="https://wwwfbm.unil.ch/html/img/foot_fbm.gif" alt="Barre verticale" border="0" /></td>
                <td>&nbsp;</td>
                <td valign="bottom" align="right"><font color="#FFFFFF">&copy; 2011 - Université de Lausanne - All right reserved</font></td>
                </tr>
            </table></td>
        </tr>
        <!-- End Footer -->
        </tbody>
    </table>
    </div>
  </body>
</html>
