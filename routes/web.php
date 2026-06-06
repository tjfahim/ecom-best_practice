<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubcategoryController;
use Illuminate\Support\Facades\Route;


Route::resource('categories', CategoryController::class);
Route::resource('subcategories', SubcategoryController::class);

// Slug-based product view must be defined before the resource route
// to prevent Laravel from treating 'view' as a resource {product} parameter.
Route::get('products/view/{slug}', [ProductController::class, 'show'])
     ->name('products.slug');

Route::resource('products', ProductController::class);

Route::get('/', [HomeController::class, 'index'])->name('home');
