<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class CatalogController extends Controller
{
    public function products(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate(['search' => ['nullable', 'string', 'max:100'], 'category' => ['nullable', 'string'], 'brand' => ['nullable', 'string'], 'featured' => ['nullable', 'boolean'], 'sort' => ['nullable', 'in:name,-name,newest'], 'per_page' => ['nullable', 'integer', 'min:1', 'max:48']]);
        $products = Product::query()->active()->with(['translations', 'category.translations', 'brand.translations'])->search($validated['search'] ?? null)
            ->when($validated['category'] ?? null, fn ($q, $slug) => $q->whereHas('category', fn ($category) => $category->where('slug', $slug)))
            ->when($validated['brand'] ?? null, fn ($q, $slug) => $q->whereHas('brand', fn ($brand) => $brand->where('slug', $slug)))
            ->when(array_key_exists('featured', $validated), fn ($q) => $q->where('is_featured', $validated['featured']))
            ->when(($validated['sort'] ?? 'newest') === 'name', fn ($q) => $q->orderByTranslation('name'))
            ->when(($validated['sort'] ?? 'newest') === '-name', fn ($q) => $q->orderByTranslation('name', 'desc'))
            ->when(($validated['sort'] ?? 'newest') === 'newest', fn ($q) => $q->latest())
            ->paginate($validated['per_page'] ?? 12)->withQueryString();

        return ProductResource::collection($products)->additional(['message' => 'Products retrieved successfully.']);
    }

    public function product(string $slug): ProductResource
    {
        return new ProductResource(Product::active()->with(['translations', 'category.translations', 'brand.translations'])->where('slug', $slug)->firstOrFail());
    }

    public function filters(): JsonResponse
    {
        $data = Cache::remember('catalog.filters.'.app()->getLocale(), 3600, fn () => [
            'categories' => Category::active()->with('translations')->orderBy('sort_order')->get(),
            'brands' => Brand::active()->with('translations')->orderByTranslation('name')->get(),
        ]);

        return response()->json(['data' => $data, 'message' => 'Catalog filters retrieved successfully.', 'errors' => null]);
    }
}
