<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        if(request()->isMethod('post')){
            return [
                'product_name' => 'required|string|max:255',
                'product_price' => 'required|integer',
                'product_image' => 'required|mimes:png,jpg,jpeg,webp',
            ];
        }else{
            return [
                'product_name' => 'nullable|string|max:255',
                'product_price' => 'nullable|integer',
                'product_image' => 'nullable|mimes:png,jpg,jpeg,webp',
            ];
        }
    }
}
