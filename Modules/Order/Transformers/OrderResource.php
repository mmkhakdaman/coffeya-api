<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Customer\Entities\Address;
use Modules\Customer\Transformers\AddressResource;
use Modules\Table\Transformers\TableResource;

class OrderResource extends JsonResource
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
            'customer' => $this->customer,
            'table' => $this->whenLoaded('table', TableResource::make($this->table)),
            'is_delivery' => $this->is_delivery,
            'address' => $this->whenLoaded('address', AddressResource::make($this->address)),
            'is_packaging' => $this->is_packaging,
            'description' => $this->description,
            'status' => $this->status,
            'pending_at' => $this->pending_at,
            'confirmed_at' => $this->confirmed_at,
            'completed_at' => $this->completed_at,
            'cancelled_at' => $this->cancelled_at,
            'post_cost' => $this->post_cost,
            'order_price' => $this->order_price,
            'total_price' => $this->total_price,
            'items' => OrderItemResource::collection($this->items),
        ];
    }
}
