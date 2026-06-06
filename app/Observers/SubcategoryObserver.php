<?php

namespace App\Observers;

use App\Models\Subcategory;
use App\Services\SlugService;

class SubcategoryObserver
{
    public function creating(Subcategory $subcategory): void
    {
        $subcategory->slug = SlugService::generate($subcategory, $subcategory->name);
    }

    public function updating(Subcategory $subcategory): void
    {
        // Only regenerate slug when name changes to avoid unnecessary DB writes.
        if ($subcategory->isDirty('name')) {
            $subcategory->slug = SlugService::generate($subcategory, $subcategory->name, $subcategory->id);
        }
    }
}
