<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Http\Requests\SubcategoryStoreRequest;
use App\Http\Requests\SubcategoryUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $subcategories = Subcategory::with('category')
            ->withCount('products')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', $request->status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('subcategories.index', compact('subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::active()->get();

        return view('subcategories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubcategoryStoreRequest $request): RedirectResponse
    {
        Subcategory::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('subcategories.index')
            ->with('success', 'Subcategory created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subcategory $subcategory): View
    {
        $subcategory->load('category', 'products');
        $subcategory->loadCount('products');

        return view('subcategories.show', compact('subcategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subcategory $subcategory): View
    {
        $categories = Category::active()->get();

        return view('subcategories.edit', compact('subcategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubcategoryUpdateRequest $request, Subcategory $subcategory): RedirectResponse
    {
        $subcategory->update([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('subcategories.index')
            ->with('success', 'Subcategory updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subcategory $subcategory): RedirectResponse
    {
        $subcategory->delete();

        return redirect()
            ->route('subcategories.index')
            ->with('success', 'Subcategory deleted successfully.');
    }
}
