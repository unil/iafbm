<?php

require_once(dirname(__file__).'/Script.php');

class iafbmUpdateScript extends iafbmScript {

    function run() {
        try {
            $this->update_project();
            $this->update_libs();
            $this->create_database_structure();
            $this->create_database_catalogs();
        } catch(Exception $e) {
            $message = $e->getMessage();
            $this->log("ERROR: {$message}");
            throw $e;
        }
        // Displays run time
        $this->log();
        $this->log('Runtime: '.$this->timer_lapse().' seconds');
    }

    protected function update_project() {
        $this->log('Updating project...');
        // Updates git project
        exec('git pull git@github.com:unil/iafbm.git > /dev/null 2>&1', $output, $status);
        if ($status) throw new xException('Error updating project', $output);
        $this->log('OK', 1);
    }

    protected function update_libs() {
        $this->log('Updating libraries...');
        // Updates libs
        $libpath = dirname(xContext::$libpath);
        exec("svn up {$libpath}", $output, $status);
        if ($status) throw new xException('Error updating libs', $output);
        $this->log('OK', 1);
    }

    protected function create_database_structure() {
        $this->log('Creating database...');
        $this->confirm('This action will destroy and create the database from scratch. Are you sure?');
        // Updates database
        exec("cd ../sql; ./merge.sh; cd -;", $output, $status);
        $user = xContext::$config->db->user;
        $password = xContext::$config->db->password;
        $database = xContext::$config->db->database;
        $file_pristine = dirname(__file__).'/../sql/merged.sql';
        $file = 'merged.sql';
        // Substitues {db-name} with actual profile db name
        exec("cat {$file_pristine} | sed s/{db-name}/{$database}/ > $file", $output, $status);
        if ($status) throw new xException('Error creating SQL dump file', $output);
        // Executes SQL dump
        $cmd = "mysql --default-character-set=utf8 -u{$user} -p\"{$password}\" {$database} < {$file}";
        exec($cmd, $output, $status);
        if ($status) throw new xException('Error updating database', 500, $output);
        // Cleans SQL temporary file
        exec("rm -f {$file}", $output, $status);
        if ($status) throw new xException('Error cleaning SQL dump file', 500, $output);
        $this->log('OK', 1);
    }

    protected function create_database_catalogs() {
        $this->log('Creating database catalogs...');
        // Create catalogue entrie
        require_once('../sql/900_catalogue_data.php');
        foreach($catalogue_data as $model_name => $items) {
            $this->log("Creating '{$model_name}'", 1);
            foreach($items as $item) xModel::load($model_name, $item)->put();
        }
        $this->log('OK', 1);
    }
}

new iafbmUpdateScript();

?>