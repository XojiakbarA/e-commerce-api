<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShopRequest extends FormRequest
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'title' => 'required|string',
            'region_id' => 'required|integer',
            'district_id' => 'required|integer',
            'street' => 'required|string',
            'home' => 'required|string',
            'phone' => 'required|string|min:14|max:14',
            'bg_image' => 'nullable|image',
            'av_image' => 'nullable|image'
        ];
    }
}
