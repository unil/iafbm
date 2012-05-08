<?php

class iaAuth extends xAuth {
   /**
    * Persmissions configuration:
    * - Each group permission is read sequentially.
    * - When a user is in multiple groups,
    * array(
    *     'role' => array(
    *         'models' => array(
    *             '*' => 'R', // Allow reading of all models
    *             'person' => 'crud',
    *             'address' => 'cru',
    *             'email' => null // Revokes all action on email model
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
            )
        ),
        'fbm-iafbm-releve-g, fbm-iafbm-admin-g' => array(
            'models' => array(
                '*' => 'CRUD'
            )
        )
    );

    protected $role_separator = ';';

    function set_from_aai() {
        $authenticated = isset(
            $_SERVER['HTTP_SHIB_PERSON_UID'],
            $_SERVER['HTTP_SHIB_SWISSEP_HOMEORGANIZATION']
        );
        $username = $authenticated ? implode(
            '@',
            array(
                $_SERVER['HTTP_SHIB_PERSON_UID'],
                $_SERVER['HTTP_SHIB_SWISSEP_HOMEORGANIZATION']
            )
        ) : 'guest';
        $roles = @$_SERVER['HTTP_SHIB_CUSTOM_UNILMEMBEROF'];
        $this->set($username, $roles);
    }
}