<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'birth_date' => $this->birth_date,
            'image' => new ImageResource($this->image),
            'all_orders_count' => $this->orders->count(),
            'awaiting_payment_count' => $this->transactions->where('status', 'pending')->count(),
            'awaiting_shipment_count' => $this->transactions->where('status', 'approved')->count(),
            'awaiting_delivery_count' => $this->orders->where('status', 'shipped')->count(),
            'shop' => new ShopResource($this->shop),
            'role' => $this->role
        ];
    }
}
