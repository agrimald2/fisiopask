<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Subfamily;
use App\Models\Schedule;
use App\Models\Office;


use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Inertia\Inertia;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $model = doctors()->index($request->searchQuery);

        $user = auth()->user();

        $model->appends($_GET)->links();

        if ($user->hasRole('assistant')) {
            return inertia('Backend/Dynamic/Grid', [
                'model' => $model->items(),

                'links' => $model->linkCollection(),

                'parameters' => $request->all(),

                'title' => 'Lista de Doctores',

                'grid' => 'Backend/Doctors/gridAssistant.js',
            ]);
        }

        return inertia('Backend/Dynamic/Grid', [
            'model' => $model->items(),

            'links' => $model->linkCollection(),

            'parameters' => $request->all(),

            'title' => 'Lista de Doctores',

            'create' => route('doctors.create'),

            'grid' => 'Backend/Doctors/grid.js',
        ]);
    }

    public function create()
    {
        $workspaces = workspaces()->index();

        Inertia::share('doctorsConfig', config('doctors'));

        return inertia('Backend/Doctors/CreateEdit', compact('workspaces'));
    }

    public function store(Request $request)
    {


        $validated = $request->validate([
            'name' => 'required',
            'lastname' => 'required',
            'user.email' => 'required|email|unique:users,email',
            'user.password' => 'required|min:5',
            'birth_date' => 'required|date',
            'sex' => 'required',
            'phone' => 'required',
            'document_type' => 'required',
            'document_reference' => 'required',
            'workspace_id' => '',
        ]);

        doctors()->create($validated);

        toast('success', 'Doctor creado correctamente');
        return redirect()->route('doctors.index');
    }

    public function edit($id)
    {
        $model = doctors()->show($id);

        $specialties = doctorSpecialties()->index();

        $subfamilies = Subfamily::query()
            ->orderBy('id', 'desc')
            ->with('doctors')
            ->get();

        $workspaces = workspaces()->index();

        Inertia::share('doctorsConfig', config('doctors'));
        return inertia('Backend/Doctors/CreateEdit', compact('model', 'specialties', 'subfamilies', 'workspaces'));
    }


    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'user.name' => 'required',
            'user.email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($doctor->user_id),
            ],
            'user.password' => 'nullable|min:5',

            'name' => 'required',
            'lastname' => 'required',

            'birth_date' => 'required|date',
            'sex' => 'required',
            'phone' => 'required',
            'document_type' => 'required',
            'document_reference' => 'required',
            'workspace_id' => '',
        ]);

        doctors()->update($doctor, $validated);

        toast('success', 'Doctor actualizado con éxito');
        return redirect()->route('doctors.index');
    }


    public function destroy(Doctor $doctor)
    {
        $schedules = Schedule::query()->where('doctor_id', $doctor->id)->get();

        foreach($schedules as $schedule)
        {
            $schedule->delete();
        }

        doctors()->destroy($doctor);
        toast('success', "Doctor '{$doctor->user->name}' eliminado.");
        return redirect()->route('doctors.index');
    }


    public function specialtiesAdd(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|numeric',
            'specialty_id' => 'required|numeric',
        ]);

        doctorSpecialties()->doctorAdd($request->doctor_id, $request->specialty_id);

        return redirect()->route('doctors.edit', $request->doctor_id);
    }


    public function specialtiesRemove(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|numeric',
            'specialty_id' => 'required|numeric',
        ]);

        doctorSpecialties()->doctorRemove($request->doctor_id, $request->specialty_id);

        return redirect()->route('doctors.edit', $request->doctor_id);
    }

    public function subfamiliesAdd(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|numeric',
            'subfamily_id' => 'required|numeric',
        ]);

        $subfamily = SubFamily::findOrFail($request->subfamily_id);

        Doctor::findOrFail($request->doctor_id)
            ->subfamilies()
            ->syncWithoutDetaching($subfamily);

        return redirect()->route('doctors.edit', $request->doctor_id);
    }


    public function subfamiliesRemove(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|numeric',
            'subfamily_id' => 'required|numeric',
        ]);

        $subfamily = SubFamily::findOrFail($request->subfamily_id);

        Doctor::findOrFail($request->doctor_id)
            ->subfamilies()
            ->detach($subfamily);

        return redirect()->route('doctors.edit', $request->doctor_id);
    }

    public function wame($phone)
    {
        $appointment = Appointment::with('patient')->find($phone);

        if( $appointment->start <= '12:00'){
            $appointment->start = $appointment->start . 'AM';
        }else{
            $appointment->start = $appointment->start . 'PM';
        };

        $office = $appointment->schedule->office;
        $office_indications = $office->indications;
        $office_address = $office->address;
        $office_reference = $office->reference;
        $office_maps_link = $office->maps_link;

        $patientURL = "https://fisiosalud.pe/area/patients/login/".$appointment->patient->dni."/".$appointment->patient->token;
        $text = "*FISIOSALUD: RECORDATORIO DE CITA*%0a%0a*Fecha de cita:*%0a$appointment->date%0a*Hora de cita:*%0a$appointment->start%0a%0a*DIRECCION:*%0a%0aEstamos $office_indications en $office_address.%0a%0aReferencia: $office_reference.%0a%0aVer mapa aqui:%0a%0a$office_maps_link%0a%0a%0a*PAGOS DE CITA*%0a%0aPor favor paga tus Citas aquí. de tres formas:%0a%0a 1. Transferencia Bancaria a nombre de Fast Recover Sac al BBVA: %0a%0a 001100230200074705 %0a%0a CCI 01102300020007470591 %0a%0a 2. Tarjeta de Credito o Debito, aqui: %0ahttps://fisiosalud.pe/tarifario%0a 3. PLIN o YAPE: %0a 996726834 %0a (Igor Grimaldo) %0a Si usas YAPE debes indicar destino *Otro Banco* %0a%0a *IMPORTANTE* %0aPor favor recuerde que solo se aceptan cancelaciones o reprogramaciones 24 horas antes u 8 horas laborables previas a su Cita programada; de lo contrario perdera su cita y el pago de su tarifa promocional.";

        $gateway = "https://wa.me/".$appointment->patient->phone."?text=".$text;
        return response('', 409)->header('X-Inertia-Location', $gateway);
    }
}
