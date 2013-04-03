<?php
require_once(dirname(__file__).'/../../xfm/lib/Core/Bootstrap.php');

/**
 * Project specific bootsrap extension.
 * Includes custom iafbm-specific xfreemwork classes extensions.
 * @package iafbm-library
 */
class iaBootstrap extends xBootstrap {

    /**
     * Includes project-specific libraries.
     */
    function setup_includes_externals() {
        parent::setup_includes_externals();
        require_once(xContext::$basepath.'/lib/iafbm/xfm/iaAuth.php');
        require_once(xContext::$basepath.'/lib/iafbm/xfm/iaModelMysql.php');
        require_once(xContext::$basepath.'/lib/iafbm/xfm/iaJournalingModelMysql.php');
        require_once(xContext::$basepath.'/lib/iafbm/xfm/iaExtRestController.php');
    }

    /**
     * Creates project-specific auth instance.
     */
    function setup_auth() {
        xContext::$log->log("Setting up iaAuth", $this);
        xContext::$auth = new iaAuth();
    }
}
