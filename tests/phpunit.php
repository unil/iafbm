<?php

$lib = '../iafbm/lib/PhpUnit';
$pear = '../iafbm/lib/PEAR';

set_include_path(get_include_path() . PATH_SEPARATOR . $pear);

require_once("{$lib}/phpunit.php");

?>
