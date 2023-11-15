<?php

namespace Modules\Tenant\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TenantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo' => $this->logo ? tenant_asset($this->logo) : url('/images/no-image.jpg'),
            'phone' => $this->phone,
            'address' => $this->address,
            'location' => $this->location,
            'cost_of_post' => $this->cost_of_post ?? 10000,
            'domain' => DomainResource::make($this->whenLoaded('domain')),
        ];
    }
}
