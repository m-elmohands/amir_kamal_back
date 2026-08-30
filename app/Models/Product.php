<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model implements TranslatableContract
{
    use HasFactory, SoftDeletes, Translatable;

    public array $translatedAttributes = ['name', 'summary', 'description', 'specifications'];

    protected $fillable = ['category_id', 'brand_id', 'slug', 'sku', 'image_url', 'availability_status', 'stock_quantity', 'minimum_order_quantity', 'lead_time_days', 'is_featured', 'is_active'];

    protected function casts(): array
    {
        return ['stock_quantity' => 'integer', 'minimum_order_quantity' => 'integer', 'lead_time_days' => 'integer', 'is_featured' => 'boolean', 'is_active' => 'boolean'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, fn (Builder $q) => $q->where('sku', 'like', "%{$search}%")->orWhereTranslationLike('name', "%{$search}%")->orWhereTranslationLike('summary', "%{$search}%"));
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->whereIn('availability_status', ['in_stock', 'low_stock', 'made_to_order']);
    }
}
