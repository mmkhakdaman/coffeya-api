<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Customer\Entities\Customer;
use Modules\Customer\Transformers\CustomerResource;
use Modules\Product\Transformers\ProductResource;

class OrderItemResource extends JsonResource
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
            'customer' => $this->whenLoaded('customer', CustomerResource::make($this->customer)),
            'product' => $this->whenLoaded('product', ProductResource::make($this->product)),
            'quantity' => $this->quantity,
            'price' => $this->price,
            'total' => $this->total,
        ];
    }
}
