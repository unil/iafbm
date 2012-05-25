<?php

class iaAuth extends xAuth {
   /**
    * Roles <-> Persmissions configuration:
    * - Each group permission is read sequentially.
    * - When a user is in multiple groups,
    * array(
    *     'role' => array(
    *         'models' => array(
    *             '*' => 'R', // Allow reading of all models
    *             'person' => 'crud', // Redefines 'person' model allowed actions to 'crud'
    *             'address' => 'cru', // Redefines 'address' model allowed actions to 'cru'
    *             'email' => null     // Redefines 'email' model allowed actions to none
    *         )
    *     )
    * )
    * @var array
    */
    protected $permissions = array(
        'fbm-iafbm-g' => array(
            'models' => array(
                '*' => 'R',
                'candidat' => null,
                'version' => 'CR',
                'version_data' => 'CR',
                'version_relation' => 'CR',
                'archive' => 'CR',
                'archive_data' => 'CR'
            )
        ),
        'fbm-iafbm-releve-g, fbm-iafbm-admin-g' => array(
            'models' => array(
                '*' => 'CRUD'
            )
        ),
        'local-superuser' => array(
            'models' => array(
                '*' => 'CRUD'
            )
        )
    );

    protected $role_separator = ';';


    function __construct() {
        parent::__construct();
        $this->canonicalize();
    }

    function set_from_aai() {
        // Retrives 'username' and 'roles' data from Shibboleth
        $authenticated = isset(
            $_SERVER['HTTP_SHIB_PERSON_UID'],
            $_SERVER['HTTP_SHIB_SWISSEP_HOMEORGANIZATION'],
            $_SERVER['HTTP_SHIB_CUSTOM_UNILMEMBEROF']
        );
        $username = $authenticated ? implode(
            '@',
            array(
                $_SERVER['HTTP_SHIB_PERSON_UID'],
                $_SERVER['HTTP_SHIB_SWISSEP_HOMEORGANIZATION']
            )
        ) : 'guest';
        $roles = @$_SERVER['HTTP_SHIB_CUSTOM_UNILMEMBEROF'];
        // Development default values (!)
        $apply_development_default_auth =
            xContext::$profile == 'development' &&
            !$authenticated
        ;
        if ($apply_development_default_auth) {
            $username = @xContext::$config->dev->auth->username;
            $roles = @xContext::$config->dev->auth->roles;
        }
        // Prevents unauthenticated access
        if (!$username || !$roles) {
            throw new xException('You must be authenticated to continue', 403);
        }
        // Determines wether 'roles' have changed since last request
        $roles_have_changed = (implode(';', $this->roles()) != $roles);
        // Sets auth information
        $this->set($username, $roles);
        // Updates and stores user permissions (only if Shibboleth roles have changed)
        if (true||$roles_have_changed) {
            $permissions = $this->compute_permissions();
            $this->set($username, $roles, array('permissions' => $permissions));
        }
    }

/* TODO: make permissions on self::set().
    function set($username, $roles, $info=array()) {
        parent::set($username, $roles);
        $permissions = $this->compute_permissions();
        return parent::set(
            $username,
            $roles,
            array_merge_recursive(
                $info,
                array('permissions' => $permissions)
            ));
    }
*/

    /**
     * Returns true if user is allowed to execute $operation on $model,
     * false otherwise.
     * @param string Model name.
     * @param string Operations name ('get', 'put', 'post' or 'delete').
     */
    function is_allowed_model($name, $operation) {
        // Determines if allowed
        $map = array(
            'get' => 'R',
            'put' => 'C',
            'post' => 'U',
            'delete' => 'D'
        );
        $permissions = $this->get_permissions();
        $allowed_operations = @$permissions['models'][$name];
        $requested_operation = @$map[strtolower($operation)];
        return !!@stristr($allowed_operations, $requested_operation);
    }

    /**
     * Canonicalizes $this->permissions.
     */
    function canonicalize() {
        // Canonicalization
        // Canonicalize models permissions (per role)
        foreach ($this->permissions as $role => &$permissions) {
            $m = array();
            $models = @$permissions['models'] ? $permissions['models'] : array();
            foreach ($models as $model => $operations) {
                if ($model == '*') {
                    // Expands models wildcards
                    foreach (xModel::scan() as $model) {
                        $m[$model] = $operations;
                    }
                } else {
                    // Sets (or redifines) model operations
                    $m[$model] = $operations;
                }
            }
            // Assigns canonicalized models permissions
            $permissions['models'] = $m;
        }
        // Splits 'n-role..permissions' into '1-role..permissions'
        $p = array();
        foreach ($this->permissions as $role => &$permissions) {
            $roles = array_map('trim', explode(',', $role));
            foreach ($roles as $role) {
                $p[$role] = $permissions;
            }
        }
        $this->permissions = $p;
    }

    /**
     * Computes and returns actual user permissions.
     * @return array
     */
    protected function compute_permissions() {
        $to_array = function($string) {
            $a = array();
            for ($i=0; $i<strlen($string); $i++) $a[$i] = $string[$i];
            return $a;
        };
        $merge = function($existing_operations, $operations_to_merge) use ($to_array) {
            return implode(null, array_unique(array_merge(
                $to_array($existing_operations),
                $to_array($operations_to_merge)
            )));
        };
        //
        $active_roles = xUtil::filter_keys($this->permissions, $this->roles());
        $p = array();
        foreach ($active_roles as $role => $permissions) {
            foreach ($permissions['models'] as $model => $operations) {
                $p['models'][$model] = $merge(@$p['models'][$model], $operations);
            }
        }
        return $p;
    }

    function get_permissions() {
        return $this->info('permissions') ? $this->info('permissions') : array();
    }
}