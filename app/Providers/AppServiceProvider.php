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

        //AUTHOR
        $this->app->bind(
            'App\Http\Repositories\Interface\IAuthorRepository',
            'App\Http\Repositories\AuthorRepository'
        );

        //LOAN
        $this->app->bind(
            'App\Http\Repositories\Interface\ILoanRepository',
            'App\Http\Repositories\LoanRepository'
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
