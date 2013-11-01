<?php

/**
 * Project specific Auth.
 * Uses Shibboleth information to automatically authenticate the user.
 * @package iafbm-library
 */
class iaAuth extends xAuth {
   /**
    * Roles <-> Persmissions configuration.
    * This array defines the permissions associated with each role.
    * - Each group permission is read sequentially.
    * - When a user is in multiple groups, the computed permission of each group is merged together.
    * - This permission definition array is canonicalized into a pure model => permissions array, at construct time.
    * 
    * Model rights definitions:
    * - is a 'model' => 'allowed-rights' array, eg. array('person' => 'CR').
    * - use the conventional CRUD symbols (C:reate, R:ead, U:pdate, D:elete).
    * - can be a combinaison on any of these, or null for no access.
    * - accept wildcard (*) can be used to designate all models (and will be canonicalized in this way).
    * - are canonicalized in their declaration order, eg. subsequent déclarations override existing models rights
    *   (subsequent déclarations are not merged, they replace).
    * 
    * This example shows how to allow reading all resources, writing some and deny the 'email' resource.
    * <code>
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
    * </code>
    * Note that: currently, only model permissions are implemented.
    *
    * @see canonicalize()
    * @see compute_permissions()
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
                'archive_data' => 'CR',
                'evaluation' => null,
                'evaluation_apercu' => null,
                'evaluation_cdir' => null,
                'evaluation_contrat' => null,
                'evaluation_decision' => null,
                'evaluation_etat' => null,
                'evaluation_evaluateur' => null,
                'evaluation_evaluation' => null,
                'evaluation_preavis' => null,
                'evaluation_rapport' => null,
                'evaluation_type' => null
            )
        ),
        'fbm-iafbm-personnes-g' => array(
            'models' => array(
                'personne' => 'CRUD',
                'personne_activite' => 'CRUD',
                'personne_adresse' => 'CRUD',
                'personne_denomination' => 'R',
                'personne_email' => 'CRUD',
                'personne_formation' => 'CRUD',
                'personne_telephone' => 'CRUD',
                'personne_type' => 'R',
                'commission_membre' => 'R',
                'commission' => 'R',
                'activite' => 'R',
                'activite_nom' => 'R',
                'activite_type' => 'R',
                'adresse' => 'CRUD',
                'adresse_type' => 'R',
                'canton' => 'R',
                'etatcivil' => 'R',
                'formation' => 'R',
                'genre' => 'R',
                'pays' => 'R',
                'permis' => 'R',
                'rattachement' => 'R',
                'section' => 'R',
                'version' => 'CR',
                'version_data' => 'CR',
                'version_relation' => 'CR',
                'archive' => 'CR',
                'archive_data' => 'CR'
            )
        ),
        	
        'fbm-iafbm-evaluations-lecture-g' => array(
            'models' => array(
                'evaluation' => 'R',
                'evaluation_apercu' => 'R',
                'evaluation_cdir' => 'R',
                'evaluation_contrat' => 'R',
                'evaluation_decision' => 'R',
                'evaluation_etat' => 'R',
                'evaluation_evaluateur' => 'R',
                'evaluation_evaluation' => 'R',
                'evaluation_preavis' => 'R',
                'evaluation_rapport' => 'R',
                'evaluation_type' => 'R'
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

    /**
     * D
     *
     */
    protected $role_separator = ';';


    function __construct() {
        parent::__construct();
        $this->canonicalize();
    }

    /**
     * Sets authentication from Switch-AAI data.
     * (eg. Shibboleth cookie data)
     */
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
        $roles_have_changed = (implode($this->role_separator, $this->roles()) != $roles);
        // Sets auth information
        $this->set($username, $roles, $this->info());
        // Updates and stores user permissions (only if Shibboleth roles have changed)
        if ($roles_have_changed) {
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
                    // Ensures model exists
                    try {
                        xModel::load($model);
                    } catch (Exception $e) {
                        throw new xException("Cannot grant rights to unexisting model ({$model})");
                    }
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

    /**
     * Returns a list of permissions for the currently authenticated user.
     * @return array List of permissions.
     */
    function get_permissions() {
        return $this->info('permissions') ? $this->info('permissions') : array();
    }
}
