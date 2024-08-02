<?php

namespace App\Providers;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\OrderRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\ProductRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    
    public function register(): void
    {
        if (!file_exists(app_path('Repositories/ProductRepository.php'))) {
            dd('ProductRepository.php file does not exist at ' . app_path('Repositories/ProductRepository.php'));
        }

        if (!class_exists(ProductRepository::class)) {
            dd('ProductRepository class does not exist.');
        }

        if (!interface_exists(ProductRepositoryInterface::class)) {
            dd('ProductRepositoryInterface does not exist.');
        }
        
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // \URL::forceScheme('https');
    }
}
