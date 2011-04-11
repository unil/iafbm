<p class="title"><?php echo $d['msg'] ?></p>
<p>
  <?php
printf (
    '<a href="%s">%s</a>, <a href="%s">%s</a> %s <a href="%s">%s</a>',
    $d['previous_page'],
    _('Go back'),
    u(),
    _('return to the homepage'),
    _('or'),
    u('error/report'),
    _('report this error')
)
  ?>.
</p>
