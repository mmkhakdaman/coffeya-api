<?php

namespace Modules\Payment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
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
            'admin_id' => $this->admin_id,
            'code' => $this->code,
            'usage_limitation' => $this->usage_limitation,
            'user_limitation' => $this->user_limitation,
            'percent' => $this->percent,
            'price' => $this->price,
            'expire_at' => $this->expire_at,
            'status' => $this->status,
        ];
    }
}
