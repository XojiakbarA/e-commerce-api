<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopRequest extends FormRequest
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
            'district_id' => 'required|integer',
            'street' => 'required|string',
            'home' => 'required|string',
            'phone' => 'required|string|min:14|max:14',
            'bg_image' => 'nullable|image',
            'av_image' => 'nullable|image'
        ];
    }
}
