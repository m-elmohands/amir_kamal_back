<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['nullable', 'exists:products,id'], 'name' => ['required', 'string', 'max:120'],
            'company' => ['nullable', 'string', 'max:160'], 'email' => ['required', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40'], 'subject' => ['required', 'string', 'max:180'],
            'requested_quantity' => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'machine_model' => ['nullable', 'string', 'max:160'], 'material' => ['nullable', 'string', 'max:160'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ];
    }
}
