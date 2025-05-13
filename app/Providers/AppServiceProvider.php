<?php

namespace App\Providers;

use App\Commands\CommandInvoker;
use App\Repositories\DocumentRepository;
use App\Services\DocumentService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(DocumentRepository::class, function ($app) {
            return new DocumentRepository();
        });

        $this->app->singleton(CommandInvoker::class, function ($app) {
            return new CommandInvoker();
        });

        $this->app->singleton(DocumentService::class, function ($app) {
            return new DocumentService(
                $app->make(DocumentRepository::class),
                $app->make(CommandInvoker::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
    }
}
