<?php

use App\Models\Patient;
use App\Domain\BookAppointment\RepositoryContract;
use App\Domain\BookAppointment\FisioNextRepository;
use App\Models\Doctor;
use App\Models\Office;
use App\Models\Schedule;
use Mockery\MockInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\post;


uses(RefreshDatabase::class);



beforeAll(function () {
    app()->bind(RepositoryContract::class, FisioNextRepository::class);
});



it('redirects to `pickDay` when patient exists and have phone', function () {
    $patient = Patient::factory()->create();

    $data = ['dni' => $patient->dni];
    $url = route('bookAppointment.index.post');
    post($url, $data)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('bookAppointment.pickDay', $data));
});



it('redirects to `patientPhone` when patient phone is null', function () {
    $patient = Patient::factory()
        ->withoutPhone()
        ->create();

    $data = ['dni' => $patient->dni];

    $url = route('bookAppointment.index.post');
    post($url, $data)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('bookAppointment.patientPhone', $data));
});



it('redirects to `patientPhone` when patient exists in reniec', function () {

    $this->partialMock(FisioNextRepository::class, function (MockInterface $mock) {
        $mock->shouldReceive('attemptToCreatePatientFromReniecByDni')
            ->once()
            ->with('11111111')
            ->andReturn(true);
    });

    $data = ['dni' => '11111111'];

    post(route('bookAppointment.index.post'), $data)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('bookAppointment.patientPhone', $data));
});


it('redirects to `patient` when patient not exists (nor reniec)', function () {
    $this->mock(FisioNextRepository::class, function (MockInterface $mock) {
        $mock->shouldReceive('getPatientPhoneByDni')
            ->twice()
            ->with('22222222')
            ->andReturn(null);

        $mock->shouldReceive('doesPatientWithDniExists')
            ->once()
            ->with('22222222')
            ->andReturn(null);

        $mock->shouldReceive('attemptToCreatePatientFromReniecByDni')
            ->once()
            ->with('22222222')
            ->andReturn(false);
    });

    $data = ['dni' => '22222222'];

    post(route('bookAppointment.index.post'), $data)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('bookAppointment.patient', $data));
});



it('creates a patient in the db', function () {
    $dni = '11111111';

    $data = [
        'name' => 'name',
        'lastname1' => 'lastname1',
        'lastname2' => 'lastname2',
        'birth_date' => now()->subYears(24)->toDateString(),
        'sex' => 'sex',
        'phone' => 987654321,
    ];

    $url = route('bookAppointment.patient.post', $dni);

    post($url, $data)
        ->assertSessionDoesntHaveErrors()
        ->assertRedirect(route('bookAppointment.pickDay', $dni));

    $data2 = [
        'name' => '2name',
        'lastname1' => '2lastname1',
        'lastname2' => '2lastname2',
        'birth_date' => now()->subYears(224)->toDateString(),
        'sex' => '2sex',
        'phone' => 2987654321,
    ];

    post($url, $data2)
        ->assertSessionDoesntHaveErrors()
        ->assertRedirect(route('bookAppointment.pickDay', $dni));


    // Assert was created
    // and not updated by second request
    $data['dni'] = $dni;
    $this->assertDatabaseHas('patients', $data);

    // Assert database count eq 1
    $this->assertDatabaseCount('patients', 1);
});


it('updates patient->phone only once', function () {
    $patient = Patient::factory()->withoutPhone()->create();

    $data = [
        'phone' => 442442442442
    ];

    $url = route('bookAppointment.patientPhone.post', $patient->dni);

    post($url, $data)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('bookAppointment.pickDay', $patient->dni));


    $update = [
        'phone' => 224224224224
    ];

    post($url, $update)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('bookAppointment.pickDay', $patient->dni));

    // Assert phone was writed
    // and only once
    $this->assertDatabaseHas('patients', $data);
});


it('creates an appointment correctly', function () {
    $doctor = Doctor::factory()->create();
    $office = Office::factory()->create();

    $schedule = Schedule::factory()
        ->doctor($doctor)
        ->office($office)
        ->create();

    $patient = Patient::factory()->create();

    $date = now()->toDateString();

    $url = route('bookAppointment.pickTime.post', [
        'dni' => $patient->dni,
        'date' => $date,
    ]);

    $data = [
        'schedule_id' => $schedule->id,
    ];

    post($url, $data)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('bookAppointment.thanks'))
        ->assertSessionHas('phone', $patient->phone);

    $appointment = [
        'id' => 1,
        'date' => $date,
        'office' => $office->name,
        'start' => $schedule->start_time,
        'end' => $schedule->end_time,
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'schedule_id' => $schedule->id
    ];

    $this->assertDatabaseCount('appointments', 1);
    $this->assertDatabaseHas('appointments', $appointment);
});
