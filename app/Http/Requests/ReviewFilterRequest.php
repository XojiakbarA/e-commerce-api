<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewFilterRequest extends FormRequest
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
            'sort_by' => 'array|between:2,2',
            'sort_by.0' => 'string|in:published,text,rating,user_name,product_title,created_at',
            'sort_by.1' => 'string|in:asc,desc'
        ];
    }
}
