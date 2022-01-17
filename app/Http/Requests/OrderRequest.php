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
            'phone' => 'required|string|min:14|max:14',
            'district_id' => 'required|integer',
            'street' => 'required|string',
            'home' => 'required|string',
            'pay_mode' => 'in:cod,payme,click,uzcard'
        ];
    }
}
