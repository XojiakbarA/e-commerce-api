<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string',
            'sub_category_id' => 'required|integer',
            'brand_id' => 'required|integer',
            'description' => 'required|string',
            'stock' => 'required|integer',
            'price' => 'required|integer',
            'sale_price' => 'nullable|integer',
            'images.*' => 'nullable|image'
        ];
    }
}
