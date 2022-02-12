<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'rating' => $this->rating,
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'region' => $this->district->region->name,
            'district' => $this->district->name,
            'street' => $this->street,
            'home' => $this->home,
            'phone' => $this->phone,
            'av_image' => $this->av_image ? $this->av_image->src : null
        ];
    }
}
