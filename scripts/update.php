<?php

require_once(dirname(__file__).'/Script.php');

class iafbmUpdateScript extends iafbmScript {

    function run() {
        // Parses CLI options
        $update_project = ($this->opt('u:') == 'project');
        $update_library = ($this->opt('u:') == 'library');
        //$update_project = $update_library = ($this->opt('u'));
        $blast_database = ($this->opt('x'));
        // Runs selected actions
        if ($update_project) $this->update_project();
        if ($update_library) $this->update_libs();
        if ($blast_database) $this->create_database_structure();
        if ($blast_database) $this->create_database_catalogs();
        // Manages help display
        if (!($update_project || $update_library || $blast_database)) {
            $this->display_help();
        }
    }

    function help() {
        return array(
            "Command-line options",
            "--------------------",
            "-h\t\tDisplay this screen",
            "-u[item]\tUpdate project and libraries",
            "\t\t\t-uproject: Updates iafbm project only",
            "\t\t\t-ulibrary: Updates xfreemwork library only",
            "-x\t\tDrop and create database from scratch",
            '',
            '--------------------',
            "Examples:",
            "\t{$_SERVER['argv'][0]}\t\tdoes nothing",
            //"\t{$_SERVER['argv'][0]} -u\t\tupdates both project and libraries code",
            "\t{$_SERVER['argv'][0]} -uproject\tupdates project code only",
            "\t{$_SERVER['argv'][0]} -ulibrary\tupdates library code only",
            "\t{$_SERVER['argv'][0]} -u -x\tupdates code and blasts database (!)"
        );
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
        $basepath = dirname(xContext::$basepath);
        exec("cd {$basepath} && git submodule update && cd -", $output, $status);
        if ($status) throw new xException('Error updating git submodule(s)', $output);
        $this->log('OK', 1);
    }

    protected function create_database_structure() {
        $this->log('Creating database...');
        // Confirmation message (if applicable)
        if ($this->opt('x:') != 'yes') {
            $this->confirm('This action will destroy and create the database from scratch. Are you sure?');
        }
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
        // (one query per statement as xModel::q() can only execute one statement at a time)
        $statements = explode(';', file_get_contents($file));
        foreach ($statements as $statement) {
            // Skips empty statements
            if (!preg_match('/[a-zA-Z]+/', $statement)) continue;
            // Execute statement
            try {
                xModel::q($statement);
            } catch (Exception $e) {
                throw new xException($e->getMessage(), 500, array($statement));
            }
        }
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
