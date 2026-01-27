<?php

namespace App\Http\Resources\Mcp;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'fullname' => $this->fullname,
            'specialties' => $this->whenLoaded('specialties', function () {
                return $this->specialties->map(function ($specialty) {
                    return [
                        'id' => $specialty->id,
                        'name' => $specialty->name,
                    ];
                });
            }),
        ];
    }
}
