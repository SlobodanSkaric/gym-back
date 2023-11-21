<?php

namespace App\Providers;

use App\Contracts\LoginRequestInterface;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\CoachLoginRequest;
use App\Http\Requests\UserLoginRequest;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(LoginRequestInterface::class, UserLoginRequest::class);
        $this->app->bind(LoginRequestInterface::class, AdminLoginRequest::class);
        $this->app->bind(LoginRequestInterface::class, CoachLoginRequest::class);
    }


}
