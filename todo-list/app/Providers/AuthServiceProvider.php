<?php

namespace App\Providers;

use \Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use App\Containers\RolesContainer;
use Illuminate\Contracts\Auth\UserProvider as UserProviderInterface;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];


    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {

        $this->app->bind('RolesContainer', RolesContainer::class);
        $this->app->bind(UserProviderInterface::class, UserProvider::class);

        $gate->define('hasRole', 'RolesContainer@userMatchesRole');

        $this->registerPolicies();
    }
}
