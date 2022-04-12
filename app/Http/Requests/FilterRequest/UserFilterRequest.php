<?php

namespace App\Http\Requests\FilterRequest;

use Illuminate\Foundation\Http\FormRequest;

class UserFilterRequest extends FormRequest
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
            'first_name' => 'string',
            'last_name' => 'string',
            'email' => 'string',
            'phone' => 'string',
            'birth_date' => 'date',
            'role' => 'string',
            'sort_by' => 'array|between:2,2',
            'sort_by.0' => 'string|in:id,first_name,last_name,email,phone,birth_date,role',
            'sort_by.1' => 'string|in:asc,desc'
        ];
    }
}
