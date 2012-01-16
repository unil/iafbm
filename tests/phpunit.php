<?php

// Setups PHPUnit libs paths
$phpunit = '../iafbm/lib/PhpUnit';
$pear = '../iafbm/lib/PEAR';
set_include_path(get_include_path() . PATH_SEPARATOR . $pear);
set_include_path(get_include_path() . PATH_SEPARATOR . $phpunit);


// Project specific TestCase class
class PHPUnit_Iafbm {

    public static function setupBootstrap() {
        require_once('../iafbm/public/Bootstrap.php');
        return new Bootstrap();
    }

}


// Runs PHPUnit
require_once("{$phpunit}/phpunit.php");

?>
