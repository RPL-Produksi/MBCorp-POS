<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProviders extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('superadmin', function (User $user) {
            return $user->role == 'superadmin';
        });

        Gate::define('admin', function (User $user) {
            return $user->role == 'admin';
        });

        Gate::define('owner', function (User $user) {
            return $user->role == 'owner';
        });

        Gate::define('kasir', function (User $user) {
            return $user->role == 'kasir';
        });

        Gate::define('member', function (User $user) {
            return $user->role == 'member';
        });
    }
}
