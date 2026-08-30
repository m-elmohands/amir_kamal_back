<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id, 'name' => $this->name, 'slug' => $this->slug, 'sku' => $this->sku,
            'summary' => $this->summary, 'description' => $this->description,
            'specifications' => $this->specifications ?? [], 'image_url' => $this->image_url,
            'is_featured' => $this->is_featured,
            'category' => $this->whenLoaded('category', fn () => ['name' => $this->category->name, 'slug' => $this->category->slug]),
            'brand' => $this->whenLoaded('brand', fn () => $this->brand ? ['name' => $this->brand->name, 'slug' => $this->brand->slug] : null),
        ];
    }
}
