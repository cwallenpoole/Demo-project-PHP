<?php

namespace App\Containers;

use App\User;
use App\TodoEntry;
use App\TodoList;

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

        if(func_num_args() > 2) {
            $role = func_get_args();
            array_shift($role);
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
     * Restricting user ability to edit.
     *
     * @param \App\User $user
     * @param TodoEntry|TodoList $todoItem
     */
    public function userCanEdit(\App\User $user, $todoItem) {
        if(!$todoItem) {
            return false;
        }

        if(is_a($todoItem, TodoEntry::class)) {
            $todoItem = $todoItem->parent();
        }

        // Admins can do anything
        if($this->userMatchesRole($user, static::ADMIN)) {
            return true;
        // Readers can only update their private lists
        } elseif ($this->userMatchesRole($user, static::READER) && !$todoItem->is_private) {
            return false;
        }

        return !$todoItem->exists ||
            $todoItem->user_id === $user->id;

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