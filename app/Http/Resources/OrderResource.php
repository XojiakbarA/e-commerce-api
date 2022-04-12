<?php

namespace App\Http\Resources;

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
            'region' => new RegionResource($this->district->region),
            'district' => new DistrictResource($this->district),
            'street' => $this->street,
            'home' => $this->home,
            'total' => $this->total,
            'created_at' => $this->created_at->toFormattedDateString(),
            'sub_orders' => SubOrderResource::collection($this->subOrders),
            'order_products' => OrderProductResource::collection($this->orderProducts)
        ];
    }
}
