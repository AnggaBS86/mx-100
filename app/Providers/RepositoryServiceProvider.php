<?php

namespace App\Providers;

use App\Repository\AuthorRepository;
use App\Repository\AuthorRepositoryInterface;
use App\Repository\BookRepository;
use App\Repository\BookRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repository\UserRepository;
use App\Repository\UserRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AuthorRepositoryInterface::class, AuthorRepository::class);
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
