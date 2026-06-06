<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Observers\CategoryObserver;
use App\Observers\SubcategoryObserver;
use App\Observers\ProductObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Observers handle slug auto-generation, keeping Models clean (SRP).
        Category::observe(CategoryObserver::class);
        Subcategory::observe(SubcategoryObserver::class);
        Product::observe(ProductObserver::class);
    }
}
