<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description', 'is_active'];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope to fetch only active categories.
     * Used in dropdowns to prevent selecting inactive categories.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

}
