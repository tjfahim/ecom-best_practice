<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\SlugService;
use Illuminate\Database\Eloquent\Builder;

class ProductObserver
{
    public function creating(Product $product): void
    {
        $product->slug = SlugService::generate($product, $product->name);
    }

    public function updating(Product $product): void
    {
        // Only regenerate slug when name changes to avoid unnecessary DB writes.

        if ($product->isDirty('name')) {
            $product->slug = SlugService::generate($product, $product->name, $product->id);
        }
    }
}
