<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class SlugService
{
    /**
     * Generate a unique slug from the given name.
     *
     * If the slug already exists in the table, a numeric suffix is appended
     * (e.g. "electronics" → "electronics-1" → "electronics-2").
     * Pass $ignoreId to exclude the current record during update checks.
     */
    
    public static function generate(Model $model, string $name, ?int $ignoreId = null): string
    {
        $slug     = Str::slug($name);
        $original = $slug;
        $counter  = 1;

        while (
            $model::where('slug', $slug)
                  ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                  ->exists()
        ) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }
}