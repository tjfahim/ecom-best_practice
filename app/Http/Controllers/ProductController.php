<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Products are grouped by subcategory as per task requirement.
        // withCount() used here to avoid N+1 query problem.
        $subcategories = Subcategory::with([
                'category',
                'products' => function ($query) use ($request) {
                    $query->when($request->filled('search'), function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    });
                },
            ])
            ->withCount(['products as total_products_count'])
            ->when($request->filled('subcategory_id'), function ($query) use ($request) {
                $query->where('id', $request->subcategory_id);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->whereHas('products', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->get();
        // Sum across collection — not a DB query.
        $totalProducts    = $subcategories->sum('total_products_count');
        $allSubcategories = Subcategory::active()->get();

        return view('products.index', compact('subcategories', 'allSubcategories', 'totalProducts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories    = Category::active()->get();
        $subcategories = Subcategory::active()->get();

        return view('products.create', compact('categories', 'subcategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request): RedirectResponse
    {
        $data              = $request->except('image');
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = ImageService::store($request->file('image'), 'products');
        }

        Product::create($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug): View
    {
        $product = Product::with('subcategory.category')
            ->where('slug', $slug)
            ->firstOrFail();

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        $categories    = Category::active()->get();
        $subcategories = Subcategory::active()->get();

        return view('products.edit', compact('product', 'categories', 'subcategories'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(ProductUpdateRequest $request, Product $product): RedirectResponse
    {
        $data              = $request->except('image');
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = ImageService::update($request->file('image'), $product->image, 'products');
        }

        $product->update($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        ImageService::delete($product->image);

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
