<?php

namespace App\Http\Resources;

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
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'rating' => $this->rating,
            'stock' => $this->stock,
            'desc' => $this->desc,
            'category' => new CategoryResource($this->subCategory->category),
            'sub_category' => new SubCategoryResource($this->subCategory),
            'brand' => new BrandResource($this->brand),
            'shop' => new ShopResource($this->shop),
            'image' => new ProductImageResource($this->whenLoaded('image')),
            'images' => ProductImageResource::collection($this->whenLoaded('images'))
        ];
    }
}
