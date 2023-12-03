<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Contracts\LoginRequestInterface;
use App\Http\Requests\User\UserLoginRequest;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
       // $this->registerPolicies();
        $this->app->bind(LoginRequestInterface::class, UserLoginRequest::class);
        //$this->app->bind(LoginRequestInterface::class, AdminLoginRequest::class);
        //$this->app->bind(LoginRequestInterface::class, CoachLoginRequest::class);
        //
    }
}
