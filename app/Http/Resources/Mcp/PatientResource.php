<?php

namespace App\Http\Resources\Mcp;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
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
            'dni' => $this->dni,
            'name' => $this->name,
            'lastname1' => $this->lastname1,
            'lastname2' => $this->lastname2,
            'fullname' => $this->fullname,
            'phone' => $this->phone,
            'email' => $this->email,
            'birth_date' => $this->birth_date,
            'sex' => $this->sex,
        ];
    }
}
