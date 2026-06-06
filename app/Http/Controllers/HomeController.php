<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $totalCategories    = Category::count();
        $totalSubcategories = Subcategory::count();
        $totalProducts      = Product::count();

        $categories = Category::active()->with('subcategories')
                               ->latest()
                               ->take(8)
                               ->get();

        $latestProducts = Product::active()->with('subcategory')
                                  ->latest()
                                  ->take(8)
                                  ->get();

        return view('home', compact(
            'totalCategories',
            'totalSubcategories',
            'totalProducts',
            'categories',
            'latestProducts'
        ));
    }
}
