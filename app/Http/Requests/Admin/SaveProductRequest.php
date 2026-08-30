<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    public function rules(): array
    {
        $product = $this->route('product');

        return ['category_id' => ['required', 'exists:categories,id'], 'brand_id' => ['nullable', 'exists:brands,id'], 'en.name' => ['required', 'string', 'max:180'], 'en.summary' => ['required', 'string', 'max:500'], 'en.description' => ['required', 'string'], 'en.specifications_text' => ['nullable', 'string'], 'ar.name' => ['nullable', 'required_with:ar.summary,ar.description', 'string', 'max:180'], 'ar.summary' => ['nullable', 'required_with:ar.name,ar.description', 'string', 'max:500'], 'ar.description' => ['nullable', 'required_with:ar.name,ar.summary', 'string'], 'ar.specifications_text' => ['nullable', 'string'], 'slug' => ['required', 'alpha_dash', 'max:180', Rule::unique('products')->ignore($product)], 'sku' => ['required', 'string', 'max:80', Rule::unique('products')->ignore($product)], 'image_url' => ['nullable', 'url', 'max:2048'], 'is_featured' => ['nullable', 'boolean'], 'is_active' => ['nullable', 'boolean']];
    }
}
