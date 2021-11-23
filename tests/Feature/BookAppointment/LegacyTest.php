<?php

use App\Domain\BookAppointment\RepositoryContract;
use App\Domain\BookAppointment\FisioLegacyRepository;
use Mockery\MockInterface;

use function Pest\Laravel\post;


beforeAll(function () {
    app()->bind(RepositoryContract::class, FisioLegacyRepository::class);
});

function db($table)
{
    return \DB::connection(FisioLegacyRepository::CONNECTION)
        ->table($table);
}

function createPatient($data = [])
{
    $patient = collect([
        'first_name' => 'Paciente',
        'last_name' => 'Apellido',
        'sex' => 'M',
        'date_born' => '2000-03-03',
        'email' => '',
        'phone' => '1234567890',
        'dni' => '09876543'
    ])->merge($data);

    $id = db('patients')
        ->insertGetId($patient->toArray());

    return db('patients')
        ->find($id);
}

function deletePatient($patient)
{
    \DB::connection(FisioLegacyRepository::CONNECTION)
        ->table('patients')
        ->where('id', $patient->id)
        ->delete();
}

function deletePatientByDni($dni)
{
    \DB::connection(FisioLegacyRepository::CONNECTION)
        ->table('patients')
        ->where('dni', $dni)
        ->delete();
}


it('redirects to `pickDay` when patient exists and have phone', function () {
    $patient = createPatient();

    $data = ['dni' => $patient->dni];
    $url = route('bookAppointment.index.post');
    post($url, $data)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('bookAppointment.pickDay', $data));

    deletePatient($patient);
});



it('redirects to `patientPhone` when patient phone is null', function () {
    $patient = createPatient(['phone' => '']);

    $data = ['dni' => $patient->dni];

    $url = route('bookAppointment.index.post');
    post($url, $data)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('bookAppointment.patientPhone', $data));

    deletePatient($patient);
});



it('redirects to `patientPhone` when patient exists in reniec', function () {
    $this->partialMock(FisioLegacyRepository::class, function (MockInterface $mock) {
        $mock->shouldReceive('getPatientPhoneByDni')
            ->twice()
            ->with('11111111')
            ->andReturn(null);

        $mock->shouldReceive('doesPatientWithDniExists')
            ->once()
            ->with('11111111')
            ->andReturn(null);

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
    $this->mock(FisioLegacyRepository::class, function (MockInterface $mock) {
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
    $dni = 'aaaaaaaa';

    $data = [
        'name' => 'name',
        'lastname1' => 'lastname1',
        'lastname2' => 'lastname2',
        'birth_date' => now()->subYears(24)->toDateString(),
        'sex' => 'M',
        'phone' => 987654321,
    ];

    $dbCount = db('patients')->count();

    $url = route('bookAppointment.patient.post', $dni);

    post($url, $data)
        ->assertSessionDoesntHaveErrors()
        ->assertRedirect(route('bookAppointment.pickDay', $dni));

    $data2 = [
        'name' => '2name',
        'lastname1' => '2lastname1',
        'lastname2' => '2lastname2',
        'birth_date' => now()->subYears(224)->toDateString(),
        'sex' => 'F',
        'phone' => 2987654321,
    ];

    post($url, $data2)
        ->assertSessionDoesntHaveErrors()
        ->assertRedirect(route('bookAppointment.pickDay', $dni));


    // Assert was created
    // and not updated by second request
    $data['dni'] = $dni;
    $this->assertEquals(db('patients')->where('dni', $dni)->count(), 1);

    // Assert database count eq 1
    $this->assertEquals(db('patients')->count(), $dbCount + 1);

    deletePatientByDni($dni);

    $this->assertEquals(db('patients')->count(), $dbCount);
});


it('updates patient->phone only once', function () {
    $patient = createPatient(['phone' => '']);

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
    $patient = db('patients')->whereId($patient->id)->first();
    $this->assertNotNull($patient);
    $this->assertEquals($patient->phone, substr($data['phone'], -10));
});
