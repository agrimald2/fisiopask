<?php

namespace App\Http\Resources\Mcp;

use App\Models\Appointment;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
            'date' => $this->date->format('Y-m-d'),
            'start_time' => $this->start,
            'end_time' => $this->end,
            'status' => $this->getStatusName($this->status),
            'status_code' => $this->status,
            'doctor' => $this->whenLoaded('doctor', function () {
                return [
                    'id' => $this->doctor->id,
                    'name' => $this->doctor->fullname,
                ];
            }),
            'patient' => $this->whenLoaded('patient', function () {
                return [
                    'id' => $this->patient->id,
                    'dni' => $this->patient->dni,
                    'fullname' => $this->patient->fullname,
                    'phone' => $this->patient->phone,
                ];
            }),
            'office' => $this->office ?? 'Primavera',
            'created_at' => $this->created_at->toIso8601String(),
            'created_by' => $this->created_by,
        ];
    }

    /**
     * Obtener nombre legible del estado
     *
     * @param int $status
     * @return string
     */
    private function getStatusName(int $status): string
    {
        $statuses = [
            Appointment::STATUS_CONFIRMED => 'confirmed',
            Appointment::STATUS_NOT_ASSISTED => 'not_assisted',
            Appointment::STATUS_ASSISTED => 'assisted',
            Appointment::STATUS_CANCELED => 'canceled',
        ];

        return $statuses[$status] ?? 'unknown';
    }
}
