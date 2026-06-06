<?php

namespace App\Observers;

use App\Models\Category;
use App\Services\SlugService;

class CategoryObserver
{
    public function creating(Category $category): void
    {
        $category->slug = SlugService::generate($category, $category->name);
    }

    public function updating(Category $category): void
    {
        // Only regenerate slug when name changes to avoid unnecessary DB writes.
        if ($category->isDirty('name')) {
            $category->slug = SlugService::generate($category, $category->name, $category->id);
        }
    }
}
