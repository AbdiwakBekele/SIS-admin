<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Config\Config' => 'App\Policies\Config\ConfigPolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\Utility\Todo' => 'App\Policies\Utility\TodoPolicy',
        'App\Models\Plan' => 'App\Policies\PlanPolicy',
        'App\Models\Tenant' => 'App\Policies\TenantPolicy',
        'App\Models\Subscription' => 'App\Policies\SubscriptionPolicy',
        'App\Models\Subscriber' => 'App\Policies\SubscriberPolicy',
        'App\Models\Query' => 'App\Policies\QueryPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::before(function ($user, $ability) {
            return ($user->is_default || $user->hasRole('admin')) ? true : null;
        });

        // Gate::after(function ($user, $ability) {
        //     return $user->hasRole('admin');
        // });
    }
}
