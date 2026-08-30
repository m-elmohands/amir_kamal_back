<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        $category = $this->route('category');

        return $category instanceof Category
            ? $this->user()->can('update', $category)
            : $this->user()->can('create', Category::class);
    }

    public function rules(): array
    {
        return [
            'en.name' => ['required', 'string', 'max:120'],
            'en.description' => ['nullable', 'string', 'max:1000'],
            'ar.name' => ['nullable', 'required_with:ar.description', 'string', 'max:120'],
            'ar.description' => ['nullable', 'string', 'max:1000'],
            'slug' => ['required', 'alpha_dash', 'max:120', Rule::unique('categories')->ignore($this->route('category'))],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
