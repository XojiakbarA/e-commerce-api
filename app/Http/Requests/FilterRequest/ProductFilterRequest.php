<?php

namespace App\Http\Requests\FilterRequest;

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
            'id' => 'array',
            'title' => 'string',
            'cat_id' => 'integer',
            'sub_cat_id' => 'integer',
            'brand_id' => 'string',
            'rating' => 'string',
            'stock' => 'integer',
            'published' => 'boolean',
            'sort_by' => 'array|between:2,2',
            'sort_by.0' => 'string|in:published,title,stock,price,sale_price,rating,category_title,brand_title,shop_title,created_at',
            'sort_by.1' => 'string|in:asc,desc',
            'price' => 'integer',
            'sale_price' => 'integer',
            'price_min' => 'integer',
            'price_max' => 'integer',
            'category_title' => 'string',
            'brand_title' => 'string',
            'shop_title' => 'string',
        ];
    }
}
