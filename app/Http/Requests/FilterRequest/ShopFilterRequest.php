<?php

namespace App\Http\Requests\FilterRequest;

use Illuminate\Foundation\Http\FormRequest;

class ShopFilterRequest extends FormRequest
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
            'rating' => 'integer',
            'first_name' => 'string',
            'last_name' => 'string',
            'region' => 'string',
            'district' => 'string',
            'street' => 'string',
            'home' => 'string',
            'phone' => 'string',
            'sort_by' => 'array|between:2,2',
            'sort_by.0' => 'string|in:title,rating,first_name,last_name,region,district,street,home,phone',
            'sort_by.1' => 'string|in:asc,desc'
        ];
    }
}
