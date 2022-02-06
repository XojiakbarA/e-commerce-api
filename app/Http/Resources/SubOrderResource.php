<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubOrderResource extends JsonResource
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
            'name' => $this->order->name,
            'email' => $this->order->email,
            'phone' => $this->order->phone,
            'region' => new RegionResource($this->order->district->region),
            'district' => new DistrictResource($this->order->district),
            'street' => $this->order->street,
            'home' => $this->order->home,
            'title' => $this->shop->title,
            'image' => $this->shop->avImage,
            'status' => $this->status,
            'payment_status' => $this->order->transaction->status,
            'total' => $this->total,
            'created_at' => $this->created_at->toFormattedDateString(),
            'order_products' => OrderProductResource::collection($this->orderProducts),
        ];
    }
}
