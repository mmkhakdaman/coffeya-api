<?php

namespace Modules\Tenant\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class DomainResource extends JsonResource
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
            'domain' => $this->domain,
            'tenant_id' => $this->tenant_id,
        ];
    }
}
