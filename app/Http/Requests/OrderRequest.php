<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'name' => 'string|required',
            'email' => 'string|required',
            'phone' => 'required',
            'country' => 'required',
            'address' => 'required',
            'zip_code' => 'required',
            'pay_mode' => 'in:cod,payme,click,uzcard'
        ];
    }
}
