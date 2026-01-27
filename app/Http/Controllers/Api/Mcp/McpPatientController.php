<?php

namespace App\Http\Controllers\Api\Mcp;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class McpPatientController extends Controller
{
    /**
     * Buscar paciente por DNI
     *
     * @param string $dni
     * @return JsonResponse
     */
    public function show(string $dni): JsonResponse
    {
        $patient = patients()->getByDni($dni);

        if (!$patient) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'PATIENT_NOT_FOUND',
                    'message' => 'No se encontro un paciente con el DNI proporcionado',
                ],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $patient->id,
                'dni' => $patient->dni,
                'name' => $patient->name,
                'lastname1' => $patient->lastname1,
                'lastname2' => $patient->lastname2,
                'fullname' => $patient->fullname,
                'phone' => $patient->phone,
                'email' => $patient->email,
                'birth_date' => $patient->birth_date,
                'sex' => $patient->sex,
            ],
        ]);
    }

    /**
     * Crear o actualizar paciente
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'dni' => 'required|string|min:5|max:20',
            'name' => 'required|string|max:100',
            'lastname1' => 'required|string|max:100',
            'lastname2' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'birth_date' => 'required|date|before:today',
            'sex' => 'required|string|in:M,F',
            'email' => 'nullable|email|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'Error de validacion',
                    'details' => $validator->errors(),
                ],
            ], 422);
        }

        $existingPatient = patients()->getByDni($request->dni);

        if ($existingPatient) {
            $existingPatient->update([
                'name' => $request->name,
                'lastname1' => $request->lastname1,
                'lastname2' => $request->lastname2,
                'phone' => $request->phone,
                'birth_date' => $request->birth_date,
                'sex' => $request->sex,
                'email' => $request->email,
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $existingPatient->id,
                    'dni' => $existingPatient->dni,
                    'fullname' => $existingPatient->fullname,
                ],
                'message' => 'Paciente actualizado exitosamente',
            ]);
        }

        $patient = patients()->createIfNotExists([
            'dni' => $request->dni,
            'name' => $request->name,
            'lastname1' => $request->lastname1,
            'lastname2' => $request->lastname2,
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'sex' => $request->sex,
            'email' => $request->email,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $patient->id,
                'dni' => $patient->dni,
                'fullname' => $patient->fullname,
            ],
            'message' => 'Paciente registrado exitosamente',
        ], 201);
    }

    /**
     * Obtener citas futuras del paciente
     *
     * @param string $dni
     * @return JsonResponse
     */
    public function appointments(string $dni): JsonResponse
    {
        $patient = patients()->getByDni($dni);

        if (!$patient) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'PATIENT_NOT_FOUND',
                    'message' => 'No se encontro un paciente con el DNI proporcionado',
                ],
            ], 404);
        }

        $appointments = $patient->appointments()
            ->with(['doctor', 'schedule.office'])
            ->where('date', '>=', Carbon::today()->format('Y-m-d'))
            ->where('status', '!=', Appointment::STATUS_CANCELED)
            ->orderBy('date', 'asc')
            ->orderBy('start', 'asc')
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'date' => $appointment->date->format('Y-m-d'),
                    'start_time' => $appointment->start,
                    'end_time' => $appointment->end,
                    'status' => $this->getStatusName($appointment->status),
                    'status_code' => $appointment->status,
                    'doctor' => [
                        'id' => $appointment->doctor->id,
                        'name' => $appointment->doctor->fullname,
                    ],
                    'office' => $appointment->office ?? 'Primavera',
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'patient' => [
                    'id' => $patient->id,
                    'dni' => $patient->dni,
                    'fullname' => $patient->fullname,
                ],
                'appointments' => $appointments,
                'total' => $appointments->count(),
            ],
        ]);
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
