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
        'App\Models\Grade' => 'App\Policies\GradePolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //Для адміністратора
        Gate::define('admin', function($user){
            return $user->role == 'admin';
        });

        //Для викладача
        Gate::define('teach', function($user){
            return $user->role == 'teacher';
        });
    }
}
