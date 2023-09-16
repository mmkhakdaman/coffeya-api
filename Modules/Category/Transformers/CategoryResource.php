<?php

namespace Modules\Category\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Transformers\ProductResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'order' => $this->order,
            'products' => ProductResource::collection($this->products)
        ];
    }
}
