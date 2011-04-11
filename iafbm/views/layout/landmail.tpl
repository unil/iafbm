<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Okikoo</title>
    <style>
      <!--
        body {
          font-family: helvetica, "sans-serif";
          font-size: 14px;
          margin: 30px 75px
        }

        li {
          margin-bottom: 0.75em;
        }

        a {
          color: #900;
        }

        .header {
          padding-bottom: 2px;
          border-bottom: 1px solid #aaa;
        }
        
        .address, .date, .signature {
          margin-left: 368px;
        }

        .address {
          position: absolute;
          top: 130px;
          font-size: 16px;
        }
        
        .date {
          position: absolute;
          top: 300px;
        }

        .contents {
        }

        .complimentary-close {
          margin-top: 30px;
        }
        
        .footer {
          position: fixed;
          bottom: 30px; /* cf body bottom margin */
          height: 30px;
          border-top: 1px solid #900;
          font-size: 9px;
        }

        .subject {
          font-weight: bold;
          margin-top: 100px;
          margin-bottom: 25px;
        }

        .highlight {
          color: #900;
        }
      -->
    </style>
  </head>

  <body>
    <div class="header">
      <img src="http://damien.1819.ch/a/img/id/okikoo_highres.jpg" style="width:270px" alt="Okikoo"/>
    </div>

    <div class="address">
      <div style="margin-bottom:5px;font-size:0.85em;font-weight:bold"><?php echo $d['misc']['address']['mailtype'] ?></div>
      <?php echo $d['misc']['address']['company'] ?><br />
      <?php echo $d['misc']['address']['firstname'] ?>
      <?php echo $d['misc']['address']['lastname'] ?><br />
      <?php echo $d['misc']['address']['street'] ?><br />
      <?php echo $d['misc']['address']['zip'].' '.$d['misc']['address']['city'] ?>
    </div>
    <div class="date">Denges, le <?php echo xUtil::date(mktime()) ?></div>

    <div style="height:200px">
      <!-- Content padding, necesarry because of the absolute position of the adress -->
      &nbsp;
    </div>

    <div class="contents">
      <?php echo $d['contents'] ?>
    </div>

  </body>
</html>
