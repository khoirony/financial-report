<?php

namespace App\Providers;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Gate; 
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
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
        $this->registerPolicies();

        Gate::define('is-admin', function (User $user) {
            return $user->role_id === UserRole::ADMIN; 
        });

        Gate::define('is-user', function (User $user) {
            return $user->role_id === UserRole::USER;
        });
    }
}
