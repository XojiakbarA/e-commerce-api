<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'name' => $this->user->first_name,
            'rating' => $this->rating,
            'text' => $this->text,
            'image' => $this->user->image,
            // 'product' => new ProductResource($this->product),
            'published' => $this->published,
            'created_at' => $this->created_at->diffForHumans()
        ];
    }
}
