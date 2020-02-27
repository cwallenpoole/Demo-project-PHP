<?php

namespace App\Providers;

use \Illuminate\Auth\DatabaseUserProvider;
use App\User;

/**
 * We're overriding the default here because we want to account for the possibility of the
 * token expiring.
 */
class UserProvider extends DatabaseUserProvider {

    /**
     * Get the generic user.
     *
     * @param  mixed  $userData
     * @return \App\User|null
     */
    protected function getGenericUser($userData)
    {
        if ($userData) {
            $userArr = (array) $userData;
            return new User($userArr);
        }
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \Illuminate\Auth\DatabaseUserProvider::retrieveByCredentials()
     */
    public function retrieveByCredentials(array $credentials){

        // We need to make sure that fetching the api_token does not fetch and expired one.
        $user = parent::retrieveByCredentials($credentials);

        if(!$user) {
            return null;
        }

        if((array_key_exists('api_token', $credentials) || array_key_exists('token', $credentials))
            && !$user->tokenIsValid()) {
            return null;
        }

        return $user;
    }
}