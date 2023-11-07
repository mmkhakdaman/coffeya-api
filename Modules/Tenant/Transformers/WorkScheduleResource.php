<?php

namespace Modules\Tenant\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkScheduleResource extends JsonResource
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
            'work_day' => $this->work_day,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ];
    }
}
