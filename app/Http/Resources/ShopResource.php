<?php

namespace App\Http\Resources;

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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'title' => $this->title,
            'rating' => $this->rating,
            'region' => new RegionResource($this->district->region),
            'district' => new DistrictResource($this->district),
            'street' => $this->street,
            'home' => $this->home,
            'phone' => $this->phone,
            'bg_image_big' => new ImageResource($this->bgImageBig),
            'bg_image_small' => new ImageResource($this->bgImageSmall),
            'av_image' => new ImageResource($this->avImage)
        ];
    }
}
