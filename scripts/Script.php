<?php

require_once(dirname(__file__).'/../iafbm/lib/xfm/lib/Util/Script.php');

abstract class iafbmScript extends xScript {

    function setup_bootstrap() {
        // Instanciates the project specific bootstrap
        require_once(dirname(__file__).'/../iafbm/lib/iafbm/xfm/iaBootstrap.php');
        new iaBootstrap();
        // Sets a default 'script' username
        $_SERVER['HTTP_SHIB_PERSON_UID'] = 'update-script';
        $_SERVER['HTTP_SHIB_SWISSEP_HOMEORGANIZATION'] = 'localhost';
        $_SERVER['HTTP_SHIB_CUSTOM_UNILMEMBEROF'] = 'local-superuser';
        xContext::$auth->set_from_aai();
    }

    /**
     * Executes statements from the given SQL $filename within a xTransaction.
     * If not given, the xTransaction starts and ends automatically.
     * @param string Filename to execute.
     * @param xTransaction The xTransaction instance to use.
     */
    protected function execute_sql_file($filename, xTransaction $t=null) {
        // Creates and starts transaction if unmanaged (eg. $t is null)
        if ($t) {
            $transaction = $t;
        } else {
            $transaction = new xTransaction();
            $transaction->start();
        }
        // Loads SQL file and arrize statements, filtering empty statements
        $file = xContext::$basepath."/../sql/{$filename}";
        $sql = file_get_contents($file);
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        // Executes statements
        foreach ($statements as $statement) $transaction->execute_sql($statement);
        // Closes transaction if unmanaged (eg. $t argument is null)
        if (!$t) $transaction->end();
    }
}

?>