<?php

namespace App\Exports;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\PatientPayment;
use App\Models\PatientRate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MarketingPatientsExport
{
    protected array $filters;
    protected array $selectedFields;
    protected array $headers;

    public function __construct(array $filters, array $selectedFields, array $headers)
    {
        $this->filters = $filters;
        $this->selectedFields = $selectedFields;
        $this->headers = $headers;
    }

    /**
     * Exporta a CSV con streaming (sin límite de memoria)
     */
    public function streamCsv()
    {
        $filename = 'marketing-pacientes-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            
            // BOM para UTF-8 en Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($handle, $this->headers, ';');

            // Datos en streaming
            $query = $this->buildBaseQuery();
            
            foreach ($query->cursor() as $patient) {
                $row = $this->mapPatient($patient);
                fputcsv($handle, $row, ';');
                
                // Liberar memoria
                unset($row);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Construye el query base optimizado
     */
    protected function buildBaseQuery()
    {
        $query = Patient::query()
            ->select([
                'patients.id',
                'patients.name',
                'patients.lastname1',
                'patients.lastname2',
                'patients.dni',
                'patients.email',
                'patients.phone',
                'patients.birth_date',
                'patients.sex',
                'patients.district',
                'patients.created_at',
            ])
            ->whereNull('patients.deleted_at');

        $filters = $this->filters;

        // Aplicar filtro de última cita
        if (!empty($filters['last_appointment_from']) || !empty($filters['last_appointment_to'])) {
            $query->whereExists(function ($subquery) use ($filters) {
                $subquery->selectRaw(1)
                    ->from('appointments')
                    ->whereColumn('appointments.patient_id', 'patients.id');

                if (!empty($filters['last_appointment_from'])) {
                    $subquery->where('appointments.date', '>=', $filters['last_appointment_from']);
                }

                if (!empty($filters['last_appointment_to'])) {
                    $subquery->where('appointments.date', '<=', $filters['last_appointment_to']);
                }
            });
        }

        // Filtro por subfamilias (múltiples)
        if (!empty($filters['subfamily_ids']) && is_array($filters['subfamily_ids']) && count($filters['subfamily_ids']) > 0) {
            $subfamilyIds = $filters['subfamily_ids'];
            $query->whereExists(function ($subquery) use ($subfamilyIds) {
                $subquery->selectRaw(1)
                    ->from('patient_rates')
                    ->whereColumn('patient_rates.patient_id', 'patients.id')
                    ->whereIn('patient_rates.subfamily_id', $subfamilyIds);
            });
        }

        // Filtro por monto
        if (!empty($filters['min_amount']) || !empty($filters['max_amount'])) {
            $query->whereExists(function ($subquery) use ($filters) {
                $subquery->selectRaw(1)
                    ->from('patient_rates')
                    ->whereColumn('patient_rates.patient_id', 'patients.id');

                if (!empty($filters['min_amount'])) {
                    $subquery->where('patient_rates.price', '>=', $filters['min_amount']);
                }

                if (!empty($filters['max_amount'])) {
                    $subquery->where('patient_rates.price', '<=', $filters['max_amount']);
                }
            });
        }

        // Filtro: clientes que sacaron cita por la página y nunca asistieron
        if (!empty($filters['never_assisted']) && $filters['never_assisted']) {
            $query->whereExists(function ($subquery) {
                $subquery->selectRaw(1)
                    ->from('appointments')
                    ->whereColumn('appointments.patient_id', 'patients.id')
                    ->where('appointments.created_by', 'Paciente');
            })
            ->whereNotExists(function ($subquery) {
                $subquery->selectRaw(1)
                    ->from('appointments')
                    ->whereColumn('appointments.patient_id', 'patients.id')
                    ->where('appointments.status', Appointment::STATUS_ASSISTED);
            });
        }

        // Filtro por doctor
        if (!empty($filters['doctor_id'])) {
            $query->whereExists(function ($subquery) use ($filters) {
                $subquery->selectRaw(1)
                    ->from('appointments')
                    ->whereColumn('appointments.patient_id', 'patients.id')
                    ->where('appointments.doctor_id', $filters['doctor_id']);
            });
        }

        // Filtro por edad
        if (!empty($filters['min_age']) || !empty($filters['max_age'])) {
            if (!empty($filters['min_age'])) {
                $maxBirthDate = now()->subYears($filters['min_age'])->format('Y-m-d');
                $query->where('patients.birth_date', '<=', $maxBirthDate);
            }

            if (!empty($filters['max_age'])) {
                $minBirthDate = now()->subYears($filters['max_age'] + 1)->format('Y-m-d');
                $query->where('patients.birth_date', '>', $minBirthDate);
            }
        }

        // Filtro por sexo
        if (!empty($filters['sex'])) {
            $query->where('patients.sex', $filters['sex']);
        }

        return $query->orderBy('patients.name');
    }

    /**
     * Mapea un paciente a una fila
     */
    protected function mapPatient($patient): array
    {
        $row = [];
        $cachedData = null;

        foreach ($this->selectedFields as $field) {
            // Cargar datos adicionales solo si son necesarios
            if ($this->needsAdditionalData($field) && $cachedData === null) {
                $cachedData = $this->loadAdditionalData($patient->id);
            }

            $row[] = $this->getFieldValue($patient, $field, $cachedData);
        }

        return $row;
    }

    /**
     * Verifica si el campo necesita datos adicionales
     */
    protected function needsAdditionalData(string $field): bool
    {
        return in_array($field, [
            'last_appointment_date',
            'total_appointments',
            'assisted_appointments',
            'doctor_name',
            'rate_name',
            'total_paid',
        ]);
    }

    /**
     * Carga datos adicionales del paciente de forma eficiente
     */
    protected function loadAdditionalData(int $patientId): array
    {
        $data = [
            'last_appointment_date' => null,
            'total_appointments' => 0,
            'assisted_appointments' => 0,
            'doctor_name' => null,
            'rate_name' => null,
            'total_paid' => 0,
        ];

        // Cargar datos de citas en una sola consulta
        $appointmentData = DB::table('appointments')
            ->selectRaw('
                MAX(date) as last_date,
                COUNT(*) as total,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as assisted
            ', [Appointment::STATUS_ASSISTED])
            ->where('patient_id', $patientId)
            ->first();

        if ($appointmentData) {
            $data['last_appointment_date'] = $appointmentData->last_date;
            $data['total_appointments'] = $appointmentData->total ?? 0;
            $data['assisted_appointments'] = $appointmentData->assisted ?? 0;
        }

        // Último doctor (consulta simple)
        $lastDoctor = DB::table('appointments')
            ->join('doctors', 'doctors.id', '=', 'appointments.doctor_id')
            ->where('appointments.patient_id', $patientId)
            ->orderByDesc('appointments.date')
            ->value(DB::raw("CONCAT(doctors.name, ' ', doctors.lastname)"));

        $data['doctor_name'] = $lastDoctor;

        // Última tarifa
        $data['rate_name'] = DB::table('patient_rates')
            ->where('patient_id', $patientId)
            ->orderByDesc('created_at')
            ->value('name');

        // Total pagado
        $data['total_paid'] = DB::table('patient_payments')
            ->where('patient_id', $patientId)
            ->sum('ammount') ?? 0;

        return $data;
    }

    /**
     * Obtiene el valor de un campo específico
     */
    protected function getFieldValue($patient, string $field, ?array $additionalData): mixed
    {
        switch ($field) {
            case 'fullname':
                return trim("{$patient->name} {$patient->lastname1} {$patient->lastname2}");

            case 'dni':
                return $patient->dni;

            case 'email':
                return $patient->email ?? 'N/A';

            case 'phone':
                return $patient->phone ?? 'N/A';

            case 'birth_date':
                return $patient->birth_date ?? 'N/A';

            case 'age':
                if (!$patient->birth_date) {
                    return 'N/A';
                }
                try {
                    return Carbon::parse($patient->birth_date)->age;
                } catch (\Exception $e) {
                    return 'N/A';
                }

            case 'sex':
                return $patient->sex === 'M' ? 'Masculino' : ($patient->sex === 'F' ? 'Femenino' : 'N/A');

            case 'district':
                return $patient->district ?? 'N/A';

            case 'last_appointment_date':
                $date = $additionalData['last_appointment_date'] ?? null;
                return $date ? Carbon::parse($date)->format('d/m/Y') : 'Sin citas';

            case 'total_appointments':
                return $additionalData['total_appointments'] ?? 0;

            case 'assisted_appointments':
                return $additionalData['assisted_appointments'] ?? 0;

            case 'doctor_name':
                return $additionalData['doctor_name'] ?? 'N/A';

            case 'rate_name':
                return $additionalData['rate_name'] ?? 'N/A';

            case 'total_paid':
                return 'S/ ' . number_format($additionalData['total_paid'] ?? 0, 2);

            case 'created_at':
                return $patient->created_at 
                    ? Carbon::parse($patient->created_at)->format('d/m/Y')
                    : 'N/A';

            default:
                return 'N/A';
        }
    }
}
