<?php

require_once(dirname(__file__).'/../iafbm/lib/xfreemwork/lib/lib/Util/Script.php');

abstract class iafbmScript extends xScript {

    function bootstrap_location() {
        return dirname(__file__).'/../iafbm/public/Bootstrap.php';
    }

}

?>