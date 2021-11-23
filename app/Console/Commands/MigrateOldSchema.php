<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateOldSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate old schema!';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // if (!$this->confirm('Are you sure?')) {
        //     return;
        // }

        $this->line('Migrating old database');

        $this->line("Doctors table.");
        $this->migrateDoctorsTable();
        $this->info("Done");

        $this->line("Appointments table.");
        $this->migrateAppointmentsTable();
        $this->info("Done");

        $this->line("Patients table.");
        $this->migratePatients();
        $this->info("Done");

        return 0;
    }


    public function migrateAppointmentsTable()
    {
        \DB::connection('mysql_old')
            ->table('appointments as a')
            ->orderBy('a.id')
            ->join('schedules as s', 's.id', '=', 'a.schedule_id')
            ->select([
                's.start_date',
                's.end_date',
                's.doc_id',
                'a.patient_id',
                'a.deleted_at as a_deleted_at',
                's.deleted_at as s_deleted_at',
            ])
            ->chunk(800, function ($rows) {
                $records = [];
                $now = now();

                foreach ($rows as $row) {
                    if ($row->a_deleted_at) {
                        continue;
                    }

                    $start = now()->parse($row->start_date);
                    $end = now()->parse($row->end_date);

                    $records[] = [
                        'date' => $start->toDateString(),
                        'office' => 'default',
                        'start' => $start->format('H:i'),
                        'end' => $end->format('H:i'),

                        'doctor_id' => $row->doc_id,
                        'patient_id' => $row->patient_id,
                        'schedule_id' => null,

                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                Appointment::insert($records);
            });
    }


    public function migrateDoctorsTable()
    {
        DB::connection('mysql_old')
            ->table('doctors')
            ->orderBy('id')
            ->chunk(100, function ($rows) {
                $rows->each(function ($doctor) {
                    if ($doctor->deleted_at) {
                        return;
                    }

                    $user = User::query()
                        ->where('email', $doctor->email)
                        ->first();

                    if (!$user) {
                        $user = User::create([
                            'name' => $doctor->first_name . ' ' . $doctor->last_name,
                            'email' => $doctor->email,
                            'password' => $doctor->password,
                            'remember_token' => $doctor->remember_token,
                        ]);
                    }

                    $user->assignRole('doctor');

                    $doctor = Doctor::create([
                        'name' => $doctor->first_name,
                        'lastname' => $doctor->last_name,
                        'birth_date' => $doctor->born,
                        'sex' => $doctor->sex,
                        'phone' => $doctor->phone,
                        'document_type' => $doctor->type_document,
                        'document_reference' => $doctor->document
                    ]);

                    $doctor
                        ->user()
                        ->associate($user)
                        ->save();
                });
            });
    }


    public function migratePatients()
    {
        DB::connection('mysql_old')
            ->table('patients')
            ->orderBy('id')
            ->chunk(100, function ($rows) {
                $records = [];
                $now = now();

                foreach ($rows as $patient) {
                    if ($patient->deleted_at) {
                        continue;
                    }

                    $records[] = [
                        'name' => $patient->first_name,
                        'lastname1' => $patient->last_name,
                        'lastname2' => '',
                        'sex' => $patient->sex,
                        'dni' => $patient->dni,
                        'phone' => $patient->phone,
                        'email' => $patient->email,
                        'birth_date' => $patient->date_born,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                Patient::insert($records);
            });
    }
}
