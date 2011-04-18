<?php
require_once(dirname(__file__).'/../lib/xfreemwork/lib/lib/Util/Bootstrap.php');

/**
 * Project specific bootsrap extension
 */
class Bootstrap extends xBootstrap {

    function setup_includes_externals() {
        parent::setup_includes_externals();
        require_once(xContext::$basepath.'/lib/iafbm/xfreemwork/WebController.php');
    }

}