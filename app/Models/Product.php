<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    protected $fillable = [
        'subcategory_id', 'name', 'description',
        'image', 'old_price', 'new_price', 'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'old_price'  => 'decimal:2',
        'new_price'  => 'decimal:2',
    ];

    /**
     * Scope to fetch only active products.
     * Used in dropdowns to prevent selecting inactive products.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    /**
     * Accessor to reach the category directly from a product.
     * Traverses: Product → Subcategory → Category.
     */
    public function getCategoryAttribute()
    {
        return $this->subcategory->category;
    }

    public function getDiscountPercentageAttribute(): ?int
    {
        if ($this->old_price && $this->old_price > $this->new_price) {
            return (int) round(
                (($this->old_price - $this->new_price) / $this->old_price) * 100
            );
        }
        return null;
    }
}
