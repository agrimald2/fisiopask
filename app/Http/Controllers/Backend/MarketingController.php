<?php

namespace App\Http\Controllers\Backend;

use App\Exports\MarketingPatientsExport;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Family;
use App\Models\Patient;
use App\Models\Subfamily;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketingController extends Controller
{
    /**
     * Campos disponibles para exportación
     */
    protected array $availableFields = [
        'fullname' => 'Nombre Completo',
        'dni' => 'DNI',
        'email' => 'Email',
        'phone' => 'Teléfono',
        'birth_date' => 'Fecha Nacimiento',
        'age' => 'Edad',
        'sex' => 'Sexo',
        'district' => 'Distrito',
        'last_appointment_date' => 'Última Cita',
        'total_appointments' => 'Total Citas',
        'assisted_appointments' => 'Citas Asistidas',
        'doctor_name' => 'Último Licenciado',
        'rate_name' => 'Última Tarifa',
        'total_paid' => 'Total Pagado',
        'created_at' => 'Fecha Registro',
    ];

    /**
     * Muestra la vista de Marketing con filtros
     */
    public function index()
    {
        $doctors = Doctor::query()
            ->select('id', 'name', 'lastname')
            ->orderBy('name')
            ->get()
            ->map(fn($doctor) => [
                'id' => $doctor->id,
                'name' => $doctor->fullname,
            ]);

        $families = Family::query()
            ->with(['subfamilies' => fn($q) => $q->select('id', 'name', 'family_id')->orderBy('name')])
            ->select('id', 'name')
            ->orderBy('name')
            ->get()
            ->map(fn($family) => [
                'id' => $family->id,
                'name' => $family->name,
                'subfamilies' => $family->subfamilies->map(fn($sf) => [
                    'id' => $sf->id,
                    'name' => $sf->name,
                ]),
            ]);

        $availableFields = $this->availableFields;

        return inertia('Backend/Marketing/Index', compact(
            'doctors',
            'families',
            'availableFields'
        ));
    }

    /**
     * Exporta los pacientes según los filtros aplicados
     */
    public function export(Request $request)
    {
        $filters = $request->validate([
            'last_appointment_from' => 'nullable|date',
            'last_appointment_to' => 'nullable|date',
            'subfamily_ids' => 'nullable|array',
            'subfamily_ids.*' => 'integer|exists:subfamilies,id',
            'min_amount' => 'nullable|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'never_assisted' => 'nullable|boolean',
            'doctor_id' => 'nullable|integer|exists:doctors,id',
            'min_age' => 'nullable|integer|min:0|max:150',
            'max_age' => 'nullable|integer|min:0|max:150',
            'sex' => 'nullable|string|in:M,F',
            'selected_fields' => 'required|array|min:1',
            'selected_fields.*' => 'string|in:' . implode(',', array_keys($this->availableFields)),
        ]);

        $selectedFields = $filters['selected_fields'];
        $headers = array_map(fn($field) => $this->availableFields[$field], $selectedFields);

        $export = new MarketingPatientsExport($filters, $selectedFields, $headers);
        
        return $export->streamCsv();
    }

    /**
     * Obtiene estadísticas previas a la exportación (consulta simplificada para conteo)
     */
    public function preview(Request $request)
    {
        $filters = $request->validate([
            'last_appointment_from' => 'nullable|date',
            'last_appointment_to' => 'nullable|date',
            'subfamily_ids' => 'nullable|array',
            'subfamily_ids.*' => 'integer|exists:subfamilies,id',
            'min_amount' => 'nullable|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'never_assisted' => 'nullable|boolean',
            'doctor_id' => 'nullable|integer|exists:doctors,id',
            'min_age' => 'nullable|integer|min:0|max:150',
            'max_age' => 'nullable|integer|min:0|max:150',
            'sex' => 'nullable|string|in:M,F',
            'selected_fields' => 'nullable|array',
        ]);

        // Usar consulta simplificada solo para contar (sin subqueries de datos)
        $query = $this->buildCountQuery($filters);
        $count = $query->count();

        return response()->json([
            'count' => $count,
            'message' => $count > 0 
                ? "Se encontraron {$count} pacientes con los filtros aplicados."
                : "No se encontraron pacientes con los filtros seleccionados.",
        ]);
    }

    /**
     * Construye un query simplificado solo para contar (sin subqueries de datos)
     */
    protected function buildCountQuery(array $filters): \Illuminate\Database\Eloquent\Builder
    {
        $query = Patient::query()
            ->select('patients.id')
            ->whereNull('patients.deleted_at');

        // Aplicar filtro de última cita
        if (!empty($filters['last_appointment_from']) || !empty($filters['last_appointment_to'])) {
            $query->whereExists(function ($subquery) use ($filters) {
                $subquery->select(DB::raw(1))
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
                $subquery->select(DB::raw(1))
                    ->from('patient_rates')
                    ->whereColumn('patient_rates.patient_id', 'patients.id')
                    ->whereIn('patient_rates.subfamily_id', $subfamilyIds);
            });
        }

        // Filtro por monto
        if (!empty($filters['min_amount']) || !empty($filters['max_amount'])) {
            $query->whereExists(function ($subquery) use ($filters) {
                $subquery->select(DB::raw(1))
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
                $subquery->select(DB::raw(1))
                    ->from('appointments')
                    ->whereColumn('appointments.patient_id', 'patients.id')
                    ->where('appointments.created_by', 'Paciente');
            })
            ->whereNotExists(function ($subquery) {
                $subquery->select(DB::raw(1))
                    ->from('appointments')
                    ->whereColumn('appointments.patient_id', 'patients.id')
                    ->where('appointments.status', Appointment::STATUS_ASSISTED);
            });
        }

        // Filtro por doctor
        if (!empty($filters['doctor_id'])) {
            $query->whereExists(function ($subquery) use ($filters) {
                $subquery->select(DB::raw(1))
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

        return $query;
    }
}
