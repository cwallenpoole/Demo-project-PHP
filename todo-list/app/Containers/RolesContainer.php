<?php

namespace App\Containers;

use App\User;
use Illuminate\Support\Facades\Log;

/**
 * This class exists for extracting the different role information from the rest of the site.
 * It doesn't make sense for the controllers to require an understanding of the various
 * ServiceProviders.
 *
 * This also makes the migration process easier as we're going to need to add the roles values
 * to the user table.
 *
 * @author cwa
 */
class RolesContainer {
    const ADMIN = 'admin';
    const OWNER = 'owner';
    const READER = 'reader';

    /**
     * Whether a user's role fits within the provided list of roles.
     *
     * @param User $user
     * @param string|array $role
     * [@param string $role2]
     * @return boolean
     */
    public function userMatchesRole(User $user, $role) {

        Log::info('Checking values ' . var_export(func_get_args(), 1));

        if(func_num_args() > 2) {
            $roles = func_get_args();
            array_shift($roles);
        }

        if(!is_object($user)){
            return false;
        } elseif ($user->role === static::ADMIN) {
            return true;
        }

        if(!is_array($role)) {
            $role = [$role];
        }

        return in_array($user->role, $role);
    }

    /**
     * Utility function to help in lookup of the various
     *
     * @return array
     */
    public static function getRoleNames() {
        return (new \ReflectionClass(__CLASS__))->getConstants();
    }
}