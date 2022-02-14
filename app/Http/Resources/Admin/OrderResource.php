<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\OrderProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'region' => $this->district->region->name,
            'district' => $this->district->name,
            'street' => $this->street,
            'home' => $this->home,
            'total' => $this->total,
            'order_products' => OrderProductResource::collection($this->orderProducts)
        ];
    }
}
