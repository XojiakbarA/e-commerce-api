<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
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
            'id' => $this->product->id,
            'title' => $this->product->title,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'brand' => $this->product->brand->title,
            'shop' => $this->product->shop->title,
            'image' => $this->product->image
        ];
    }
}
