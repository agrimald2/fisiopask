<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Schedule;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AvailabilityExportController extends Controller
{
    /**
     * Obtiene la vista previa de disponibilidad
     */
    public function preview(Request $request)
    {
        $validated = $request->validate([
            'doctor_ids' => 'required|array|min:1',
            'doctor_ids.*' => 'numeric',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        $availability = $this->getAvailabilityData(
            $validated['doctor_ids'],
            $validated['date_from'],
            $validated['date_to']
        );

        return response()->json([
            'availability' => $availability,
            'summary' => [
                'total_doctors' => count($validated['doctor_ids']),
                'date_from' => $validated['date_from'],
                'date_to' => $validated['date_to'],
                'total_days' => Carbon::parse($validated['date_from'])->diffInDays(Carbon::parse($validated['date_to'])) + 1,
            ],
        ]);
    }

    /**
     * Genera y descarga el PDF de disponibilidad
     */
    public function exportPdf(Request $request)
    {
        $validated = $request->validate([
            'doctor_ids' => 'required|array|min:1',
            'doctor_ids.*' => 'numeric',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        $availability = $this->getAvailabilityData(
            $validated['doctor_ids'],
            $validated['date_from'],
            $validated['date_to']
        );

        $doctors = Doctor::whereIn('id', $validated['doctor_ids'])->get();

        $data = [
            'availability' => $availability,
            'doctors' => $doctors,
            'dateFrom' => Carbon::parse($validated['date_from']),
            'dateTo' => Carbon::parse($validated['date_to']),
            'generatedAt' => now(),
            'logoUrl' => 'https://fisiosalud.pe/landing/img/logo.png',
            'brandColor' => '#0cb8b6',
        ];

        $pdf = Pdf::loadView('pdf.availability', $data);
        $pdf->setPaper('a4', 'portrait');

        $filename = 'disponibilidad-fisiosalud-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Obtiene los datos de disponibilidad para los doctores y fechas seleccionadas
     */
    private function getAvailabilityData(array $doctorIds, string $dateFrom, string $dateTo): array
    {
        $startDate = Carbon::parse($dateFrom);
        $endDate = Carbon::parse($dateTo);
        
        $availability = [];

        $doctors = Doctor::whereIn('id', $doctorIds)->get();

        foreach ($doctors as $doctor) {
            $doctorSchedules = Schedule::where('doctor_id', $doctor->id)
                ->with('office')
                ->orderBy('start_time')
                ->get();

            $doctorAvailability = [
                'doctor_id' => $doctor->id,
                'doctor_name' => $doctor->fullname,
                'days' => [],
            ];

            $currentDate = $startDate->copy();
            while ($currentDate->lte($endDate)) {
                $weekDay = $currentDate->isoWeekday();
                $dateStr = $currentDate->format('Y-m-d');

                $daySchedules = $doctorSchedules->filter(function ($schedule) use ($weekDay) {
                    return (int) $schedule->week_day === $weekDay;
                });

                $isFrozen = $doctor->freezes()
                    ->where('start', '<=', $dateStr)
                    ->where('end', '>=', $dateStr)
                    ->exists();

                if (!$isFrozen && $daySchedules->count() > 0) {
                    $availableSlots = [];

                    foreach ($daySchedules as $schedule) {
                        $hasAppointment = $schedule->appointment()
                            ->where('date', $dateStr)
                            ->exists();

                        if (!$hasAppointment) {
                            $availableSlots[] = [
                                'start_time' => $schedule->start_time,
                                'end_time' => $schedule->end_time,
                                'office' => $schedule->office->name,
                            ];
                        }
                    }

                    if (count($availableSlots) > 0) {
                        $doctorAvailability['days'][] = [
                            'date' => $dateStr,
                            'day_name' => $this->getDayName($weekDay),
                            'formatted_date' => $currentDate->format('d/m'),
                            'slots' => $availableSlots,
                        ];
                    }
                }

                $currentDate->addDay();
            }

            $availability[] = $doctorAvailability;
        }

        return $availability;
    }

    /**
     * Obtiene el nombre del día en español
     */
    private function getDayName(int $weekDay): string
    {
        $days = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo',
        ];

        return $days[$weekDay] ?? '';
    }
}
