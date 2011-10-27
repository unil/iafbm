<?php

require_once(dirname(__file__).'/Script.php');

class iafbmUpdateScript extends iafbmScript {

    function run() {
        $this->update_project();
        $this->update_libs();
        $this->update_database();
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

    protected function update_database() {
        $this->log('Updating database...');
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
        $cmd = "mysql --default-character-set=utf8 -u{$user} -p{$password} {$database} < {$file}";
        exec($cmd, $output, $status);
        if ($status) throw new xException('Error updating database', $output);
        // Cleans SQL temporary file
        exec("rm -f {$file}", $output, $status);
        if ($status) throw new xException('Error cleaning SQL dump file', $output);
        $this->log('OK', 1);
    }

}

new iafbmUpdateScript();

?>