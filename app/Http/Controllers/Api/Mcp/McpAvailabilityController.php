<?php

namespace App\Http\Controllers\Api\Mcp;

use App\Http\Controllers\Controller;
use App\Models\Office;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class McpAvailabilityController extends Controller
{
    /**
     * ID del consultorio Primavera (hardcodeado)
     */
    private function getPrimaveraOfficeId(): ?int
    {
        $office = Office::where('name', 'like', '%Primavera%')->first();
        return $office ? $office->id : null;
    }

    /**
     * Obtener disponibilidad de horarios para una fecha especifica
     *
     * @param string $date Fecha en formato Y-m-d
     * @return JsonResponse
     */
    public function show(string $date): JsonResponse
    {
        try {
            $parsedDate = Carbon::parse($date);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'INVALID_DATE_FORMAT',
                    'message' => 'El formato de fecha es invalido. Use YYYY-MM-DD',
                ],
            ], 400);
        }

        if ($parsedDate->lt(Carbon::today())) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'DATE_IN_PAST',
                    'message' => 'No se puede consultar disponibilidad para fechas pasadas',
                ],
            ], 400);
        }

        $officeId = $this->getPrimaveraOfficeId();

        if (!$officeId) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'OFFICE_NOT_FOUND',
                    'message' => 'No se encontro el consultorio Primavera',
                ],
            ], 500);
        }

        $schedules = schedules()->getAvailableSchedulesFor($date, $officeId);
        $schedulesData = schedules()->scheduleCollectionToData($schedules);

        $slots = collect($schedulesData)->map(function ($schedule) {
            return [
                'schedule_id' => $schedule['id'],
                'start_time' => $schedule['start_time'],
                'end_time' => $schedule['end_time'],
                'doctor' => [
                    'id' => $schedule['doctor_id'],
                    'name' => $schedule['doctor'],
                    'specialties' => array_values($schedule['specialties']),
                ],
            ];
        })->values();

        $dayNames = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miercoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sabado',
            7 => 'Domingo',
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'date' => $date,
                'day_name' => $dayNames[$parsedDate->isoWeekday()] ?? '',
                'office' => 'Primavera',
                'total_slots' => $slots->count(),
                'slots' => $slots,
            ],
        ]);
    }
}
