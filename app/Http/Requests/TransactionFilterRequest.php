<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionFilterRequest extends FormRequest
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
            'name' => 'string',
            'email' => 'string',
            'phone' => 'string',
            'pay_mode' => 'string',
            'status' => 'string',
            'sort_by' => 'array|between:2,2',
            'sort_by.0' => 'string|in:name,email,phone,total,pay_mode,status',
            'sort_by.1' => 'string|in:asc,desc',
        ];
    }
}
