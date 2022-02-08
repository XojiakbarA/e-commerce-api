<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\ImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'title' => $this->title,
            'published' => $this->published,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'rating' => $this->rating,
            'stock' => $this->stock,
            'category_title' => $this->subCategory->category->title,
            'sub_category_title' => $this->subCategory->title,
            'brand_title' => $this->brand->title,
            'shop_title' => $this->shop->title,
            'image' => new ImageResource($this->image),
        ];
    }
}
