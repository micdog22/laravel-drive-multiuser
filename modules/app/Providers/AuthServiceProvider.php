<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot(): void
    {
        Gate::define('admin-area', fn($user) => $user->role === 'admin' && $user->is_active);
        Gate::define('impersonate', fn($user) => $user->role === 'admin' && $user->is_active);
    }
}
