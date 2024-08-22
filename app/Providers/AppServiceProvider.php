<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //USER
        $this->app->bind(
            'App\Http\Repositories\Interface\IUserRepository',
            'App\Http\Repositories\UserRepository'
        );

        //BOOK
        $this->app->bind(
            'App\Http\Repositories\Interface\IBookRepository',
            'App\Http\Repositories\BookRepository'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
