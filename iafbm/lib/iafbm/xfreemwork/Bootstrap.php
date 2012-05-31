<?php
require_once(dirname(__file__).'/../../xfm/lib/Core/Bootstrap.php');

/**
 * Project specific bootsrap extension.
 * Includes custom iafbm-specific xfreemwork classes extensions.
 * @package iafbm
 */
class Bootstrap extends xBootstrap {

    function setup_includes_externals() {
        parent::setup_includes_externals();
        require_once(xContext::$basepath.'/lib/iafbm/xfreemwork/Auth.php');
        require_once(xContext::$basepath.'/lib/iafbm/xfreemwork/Model.php');
        require_once(xContext::$basepath.'/lib/iafbm/xfreemwork/JournalingModel.php');
        require_once(xContext::$basepath.'/lib/iafbm/xfreemwork/WebController.php');
    }

    function setup_auth() {
        xContext::$log->log("Setting up iaAuth", $this);
        xContext::$auth = new iaAuth();
    }
}
