<?php

namespace App\Providers;

use \Illuminate\Auth\DatabaseUserProvider;
use App\User;

class UserProvider extends DatabaseUserProvider {

    /**
     * Get the generic user.
     *
     * @param  mixed  $user
     * @return \Illuminate\Auth\GenericUser|null
     */
    protected function getGenericUser($userData)
    {
        if (! is_null($userData)) {
            $userArr = (array) $userData;
            $user = new User($userArr);
            if($user->tokenIsValid()) {
                return $user;
            }
        }
        return null;
    }
}