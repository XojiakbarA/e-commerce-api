<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'total' => $this->order->total,
            'pay_mode' => $this->pay_mode,
            'status' => $this->status
        ];
    }
}
