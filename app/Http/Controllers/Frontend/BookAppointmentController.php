<?php

namespace App\Http\Controllers\Frontend;

use App\Domain\BookAppointment\RepositoryContract;
use App\Http\Controllers\Controller;
use App\Models\PatientPayment;
use App\Models\PatientRate;
use App\Models\Rate;
use Illuminate\Http\Request;

class BookAppointmentController extends Controller
{
    const BASE = 'Frontend/BookAppointment';

    const SESSION_OFFICE_ID = '_OFFICE_ID';


    protected $repo;

    public function __construct(RepositoryContract $repo)
    {
        $this->repo = $repo;
    }


    /**
     * Index (dni, office_id)
     */
    public function index()
    {
        $dni = request()->dni;
        $officeOptions = $this->repo->getOfficeOptions();
        return inertia(self::BASE . '/Index', compact('officeOptions', 'dni'));
    }


    public function indexPost(Request $request)
    {
        $request->validate([
            'dni' => 'required|string|min:5',
            'office_id' => 'nullable|integer'
        ]);

        $dni = str_replace(' ', '', $request->dni);

        // Save office_id
        session([
            self::SESSION_OFFICE_ID => $request->office_id
        ]);

        $patientExists = $this->repo->doesPatientWithDniExists($dni);
        $patientHasPhone = !!$this->repo->getPatientPhoneByDni($dni);

        // If existent patient & has valid phone
        if ($patientExists && $patientHasPhone) {
            // Pick day
            return redirect()->route('bookAppointment.pickDay', [
                'dni' => $dni
            ]);
        }

        // If reniec patient create with reniec
        if (!$patientExists) {
            $patientExists = $this->repo->attemptToCreatePatientFromReniecByDni($dni);
            // Rechek if has phone
            $patientHasPhone = !!$this->repo->getPatientPhoneByDni($dni);
        }

        // If patient & missing phone
        if ($patientExists && !$patientHasPhone) {
            // Confirm patient->phone
            return redirect()->route('bookAppointment.patientPhone', [
                'dni' => $dni,
            ]);
        }

        // If unknown patient
        // Register patient
        return redirect()->route('bookAppointment.patient', [
            'dni' => $dni,
        ]);
    }


    /**
     * Missing patient
     */

    public function patient($dni)
    {
        $sexOptions = $this->repo->getSexOptions();

        return inertia(self::BASE . '/Patient', compact('dni', 'sexOptions'));
    }


    public function patientPost(Request $request, $dni)
    {
        $today = now()->toDateString();

        $validated = $request->validate([
            'name' => 'required|string',
            'lastname1' => 'required|string',
            'lastname2' => 'required|string',
            'birth_date' => 'required|date|before:' . $today,
            'sex' => 'required|string',
            'phone' => 'required|integer',
        ]);

        $validated['dni'] = $dni;

        $this->repo->createPatientIfNotExists($validated);

        return redirect()->route('bookAppointment.pickDay', [
            'dni' => $dni
        ]);
    }


    /**
     * Missing patient->phone
     */

    public function patientPhone($dni)
    {
        $name = $this->repo->getPatientNameByDni($dni);

        abort_if(!$name, redirect()->route('bookAppointment.index'));

        return inertia(self::BASE . '/PatientPhone', compact('dni', 'name'));
    }

    public function patientPhonePost(Request $request, $dni)
    {
        $request->validate([
            'phone' => 'required|integer'
        ]);

        $phone = $request->phone;

        $this->repo->updatePatientPhoneIfIsMissing($dni, $phone);

        return redirect()->route('bookAppointment.pickDay', [
            'dni' => $dni
        ]);
    }


    /**
     * Pick day
     */

    public function pickDay($dni)
    {
        $name = $this->repo->getPatientNameByDni($dni);

        abort_if(!$name, redirect()->route('bookAppointment.index'));

        return inertia(self::BASE . '/PickDay', compact('dni', 'name'));
    }

    public function pickDayPost(Request $request, $dni)
    {
        $request->validate([
            'date' => 'required|date|date_format:Y-m-d'
        ]);

        $date = $request->date;

        return redirect()->route('bookAppointment.pickTime', [
            'dni' => $dni,
            'date' => $date,
        ]);
    }


    /**
     * Pick time
     */
    public function pickTime($dni, $date)
    {
        // If past date redirect to index
        if (now()->parse($date)->lt(now()->toDateString())) {
            return redirect()->route('bookAppointment.pickDay', $dni);
        }

        $officeId = session(self::SESSION_OFFICE_ID, null);

        $office = $this->repo->getOfficeById($officeId);

        $groupedSchedules = $this->repo
            ->getAvailableSchedulesGroupedByStartTime($date, $officeId)
            ->toArray();

        $specialtyOptions = $this->repo->getSpecialtyOptions();

        $lastDoctorId = $this->repo->getLastDoctorIdForPatientByDni($dni);

        $filters = [
            'doctorId' => $lastDoctorId
        ];

        return inertia(
            self::BASE . '/PickTime',
            compact('dni', 'filters', 'date', 'specialtyOptions', 'groupedSchedules', 'office')
        );
    }

    public function pickTimePost(Request $request, $dni, $date)
    {
        $request->validate([
            'schedule_id' => 'required|integer'
        ]);

        $date = $request->date;

        $schedule = $this->repo->getScheduleIfIsAvailable(
            $request->schedule_id,
            $date
        );

        if (!$schedule) {
            return redirect()->route('bookAppointment.index');
        }

        $appointment = $this->repo->makeAppointment($dni, $date, $schedule);

        if(empty($appointment->patient_id))
        {
            $appointment = $appointment[0];
        }

        $patientId = $appointment->patient_id;

        $payments = PatientPayment::query()->where('patient_id', $patientId)->get();
        $products = PatientRate::query()->where('patient_id', $patientId)->get();

        $phone = $this->repo->getPatientPhoneByDni($dni);

        //EXISTING PATIENT
        if($payments->first())
        {
            //Create Constant Rate if no rates
            /*if(!$this->patientHasActiveRates($patientId))
            {
                $constantRate = Rate::find(1);

                PatientRate::create([
                    'name' => $constantRate->name,
                    'subfamily_id' => $constantRate->subfamily_id,
                    'patient_id' => $patientId,
                    'price' => $constantRate->price,
                    'appointment_id' => $appointment->id,
                    'payed' => 0,
                    'is_product' => false,
                    'qty' => 1,
                    'sessions_total' => 1,
                    'sessions_left' => 1,
                    'state' => PatientRate::RATE_STATUS_OPEN,
                ]);
            }*/

            $credit = true;

            foreach($products as $product)
            {
                if(!$product->can_assist_bool)
                {
                    $credit = false;
                    break;
                }
            }

            //TODO @WHATSAPP SALDO A FAVOR
            if($credit)
            {
                $this->repo->sendConfirmationToPatient($dni, $appointment, 'credit');
            }
            else
            {
            //TODO @WHATSAPP SIN SALDO
                $this->repo->sendConfirmationToPatient($dni, $appointment, 'no_credit');
            }
        }
        else //NEW PATIENT
        {
            //TODO @WHATSAPP PACIENTE NUEVO
            $this->repo->sendConfirmationToPatient($dni, $appointment, 'new');
        }
        
        return $this->thanks($appointment);

        //return redirect()->route('bookAppointment.thanks')
        //    ->with('phone', $phone);
    }

    private function patientHasActiveRates($patientId)
    {
        $query = PatientRate::query()
            ->where('patient_id', $patientId)
            ->where('state', PatientRate::RATE_STATUS_OPEN)
            ->get();

        return !($query->isEmpty());
    }


    /**
     * Thanks
     */
    public function thanks($appointment)
    {
        $date = $appointment->date->format('d/m/Y');
        $startTime = $appointment->start;

        $office = $appointment->schedule->office;
        $office_indications = $office->indications;
        $office_address = $office->address;
        $office_reference = $office->reference;

        $data = compact(
            'date',
            'startTime',
            'office_indications',
            'office_address',
            'office_reference',
        );

        $appointment = $data;

        $buttonUrl = $this->repo->getThankYouPageButtonUrl();

        return inertia(self::BASE . '/Thanks', compact('appointment', 'buttonUrl'));
    }
}
