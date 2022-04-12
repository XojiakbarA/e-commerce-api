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
            'user_name' => $this->user->first_name,
            'product_title' => $this->product->title,
            'user_image' => new ImageResource($this->user->image),
            'product_image' => new ImageResource($this->product->image),
            'rating' => $this->rating,
            'text' => $this->text,
            'published' => $this->published,
            'created_at' => $this->created_at->diffForHumans()
        ];
    }
}
