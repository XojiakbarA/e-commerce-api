<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'title' => $this->name,
            'rating' => $this->attributes->rating,
            'price' => $this->price,
            'sale_price' => $this->attributes->sale_price,
            'image' => new ImageResource($this->attributes->image),
            'quantity' => $this->quantity
        ];
    }
}
