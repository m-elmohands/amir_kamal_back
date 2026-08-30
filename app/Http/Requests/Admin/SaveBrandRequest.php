<?php

namespace App\Http\Requests\Admin;

use App\Models\Brand;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        $brand = $this->route('brand');

        return $brand instanceof Brand
            ? $this->user()->can('update', $brand)
            : $this->user()->can('create', Brand::class);
    }

    public function rules(): array
    {
        return [
            'en.name' => ['required', 'string', 'max:120'],
            'en.description' => ['nullable', 'string', 'max:1000'],
            'ar.name' => ['nullable', 'required_with:ar.description', 'string', 'max:120'],
            'ar.description' => ['nullable', 'string', 'max:1000'],
            'slug' => ['required', 'alpha_dash', 'max:120', Rule::unique('brands')->ignore($this->route('brand'))],
            'logo_url' => ['nullable', 'url', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
