<?php

require_once(dirname(__file__).'/../iafbm/lib/xfreemwork/lib/lib/Util/Script.php');

abstract class iafbmScript extends xScript {

    function setup_bootstrap() {
        // Instanciates the project specific bootstrap
        require_once(dirname(__file__).'/../iafbm/public/Bootstrap.php');
        new Bootstrap();
        // Sets a default 'script' username
        xContext::$auth->set('script', array());
    }
}

?>