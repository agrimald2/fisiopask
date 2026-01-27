<?php

namespace App\Http\Controllers\Api\Mcp;

use App\Domain\Appointments\AppointmentCanceler;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Office;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class McpAppointmentController extends Controller
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
     * Ver detalle de una cita
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $appointment = Appointment::with(['patient', 'doctor', 'schedule.office'])
            ->find($id);

        if (!$appointment) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'APPOINTMENT_NOT_FOUND',
                    'message' => 'No se encontro la cita especificada',
                ],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
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
                'patient' => [
                    'id' => $appointment->patient->id,
                    'dni' => $appointment->patient->dni,
                    'fullname' => $appointment->patient->fullname,
                    'phone' => $appointment->patient->phone,
                ],
                'office' => $appointment->office ?? 'Primavera',
                'created_at' => $appointment->created_at->toIso8601String(),
                'created_by' => $appointment->created_by,
            ],
        ]);
    }

    /**
     * Agendar nueva cita
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'patient_dni' => 'required|string|min:5|max:20',
            'schedule_id' => 'required|integer|exists:schedules,id',
            'date' => 'required|date|date_format:Y-m-d|after_or_equal:today',
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

        $patient = patients()->getByDni($request->patient_dni);

        if (!$patient) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'PATIENT_NOT_FOUND',
                    'message' => 'No se encontro un paciente con el DNI proporcionado. Debe registrar al paciente primero.',
                ],
            ], 404);
        }

        $existingAppointment = Appointment::where('date', $request->date)
            ->where('patient_id', $patient->id)
            ->where('status', '!=', Appointment::STATUS_CANCELED)
            ->first();

        if ($existingAppointment) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'PATIENT_HAS_APPOINTMENT',
                    'message' => 'El paciente ya tiene una cita agendada para esta fecha',
                    'existing_appointment_id' => $existingAppointment->id,
                ],
            ], 409);
        }

        $schedule = schedules()->available($request->schedule_id, $request->date);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'SCHEDULE_NOT_AVAILABLE',
                    'message' => 'El horario seleccionado ya no esta disponible',
                ],
            ], 409);
        }

        $appointment = appointments()->make(
            $request->date,
            $schedule,
            $patient
        );

        return response()->json([
            'success' => true,
            'data' => [
                'appointment_id' => $appointment->id,
                'date' => $appointment->date->format('Y-m-d'),
                'start_time' => $appointment->start,
                'end_time' => $appointment->end,
                'status' => 'confirmed',
                'doctor' => [
                    'id' => $appointment->doctor->id,
                    'name' => $appointment->doctor->fullname,
                ],
                'patient' => [
                    'dni' => $patient->dni,
                    'name' => $patient->fullname,
                ],
                'office' => $appointment->office ?? 'Primavera',
            ],
            'message' => 'Cita agendada exitosamente',
        ], 201);
    }

    /**
     * Reprogramar cita existente
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function reschedule(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|integer|exists:schedules,id',
            'date' => 'required|date|date_format:Y-m-d|after_or_equal:today',
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

        $appointment = Appointment::with(['patient', 'doctor'])->find($id);

        if (!$appointment) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'APPOINTMENT_NOT_FOUND',
                    'message' => 'No se encontro la cita especificada',
                ],
            ], 404);
        }

        if ($appointment->status == Appointment::STATUS_CANCELED) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'APPOINTMENT_CANCELED',
                    'message' => 'No se puede reprogramar una cita cancelada',
                ],
            ], 400);
        }

        if ($appointment->status == Appointment::STATUS_ASSISTED) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'APPOINTMENT_COMPLETED',
                    'message' => 'No se puede reprogramar una cita ya atendida',
                ],
            ], 400);
        }

        $schedule = schedules()->available($request->schedule_id, $request->date);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'SCHEDULE_NOT_AVAILABLE',
                    'message' => 'El horario seleccionado ya no esta disponible',
                ],
            ], 409);
        }

        $appointment->start = $schedule->start_time;
        $appointment->end = $schedule->end_time;
        $appointment->doctor_id = $schedule->doctor->id;
        $appointment->schedule_id = $schedule->id;
        $appointment->office = $schedule->office->name;
        $appointment->date = $request->date;
        $appointment->status = Appointment::STATUS_CONFIRMED;
        $appointment->reeschedule_by = 'MCP API';
        $appointment->save();

        return response()->json([
            'success' => true,
            'data' => [
                'appointment_id' => $appointment->id,
                'date' => $appointment->date->format('Y-m-d'),
                'start_time' => $appointment->start,
                'end_time' => $appointment->end,
                'status' => 'confirmed',
                'doctor' => [
                    'id' => $schedule->doctor->id,
                    'name' => $schedule->doctor->fullname,
                ],
            ],
            'message' => 'Cita reprogramada exitosamente',
        ]);
    }

    /**
     * Cancelar cita
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $appointment = Appointment::with('patient')->find($id);

        if (!$appointment) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'APPOINTMENT_NOT_FOUND',
                    'message' => 'No se encontro la cita especificada',
                ],
            ], 404);
        }

        if ($appointment->status == Appointment::STATUS_CANCELED) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'APPOINTMENT_ALREADY_CANCELED',
                    'message' => 'La cita ya se encuentra cancelada',
                ],
            ], 400);
        }

        if ($appointment->status == Appointment::STATUS_ASSISTED) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'APPOINTMENT_COMPLETED',
                    'message' => 'No se puede cancelar una cita ya atendida',
                ],
            ], 400);
        }

        $canceler = new AppointmentCanceler();
        $canceler->cancel($appointment);

        return response()->json([
            'success' => true,
            'message' => 'Cita cancelada exitosamente',
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
