<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKwhRequest extends StoreKwhRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['photos'] = ['nullable', 'array'];
        $rules['photos.*'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'];
        return $rules;
    }
}
