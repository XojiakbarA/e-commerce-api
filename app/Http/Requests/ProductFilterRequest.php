<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
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
            'title' => 'string',
            'cat_id' => 'integer',
            'sub_cat_id' => 'integer',
            'brand_id' => 'string',
            'rating' => 'string',
            'avail' => 'boolean',
            'sort_by' => 'array|between:2,2',
            'sort_by.0' => 'string|in:published,title,stock,price,sale_price,rating,category,brand,shop,created_at',
            'sort_by.1' => 'string|in:asc,desc',
            'price_min' => 'integer',
            'price_max' => 'integer'
        ];
    }
}
