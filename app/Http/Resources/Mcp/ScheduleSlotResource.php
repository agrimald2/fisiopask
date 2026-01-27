<?php

namespace App\Http\Resources\Mcp;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleSlotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'schedule_id' => $this->id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'doctor' => [
                'id' => $this->doctor_id,
                'name' => $this->whenLoaded('doctor', function () {
                    return $this->doctor->fullname;
                }),
                'specialties' => $this->whenLoaded('doctor', function () {
                    return $this->doctor->specialties->pluck('name')->toArray();
                }),
            ],
        ];
    }
}
