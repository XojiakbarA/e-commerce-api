<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
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
            'id' => 'integer',
            'user_name' => 'string',
            'rating' => 'integer',
            'text' => 'string',
            'product_title' => 'string',
            'published' => 'boolean',
            'sort_by' => 'array|between:2,2',
            'sort_by.0' => 'string|in:id,published,text,rating,user_name,product_title,created_at',
            'sort_by.1' => 'string|in:asc,desc'
        ];
    }
}
