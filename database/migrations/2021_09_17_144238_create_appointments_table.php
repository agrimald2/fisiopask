<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string("created_by")->nullable();

            $table->date('date');
            $table->string('office');

            $table->string('start');
            $table->string('end');

            $table->enum('status', [1, 2, 3, 4])
                ->default(1)
                ->comment('1-Confirmado, 2-No asistio, 3-Asistió, 4-Cancelada');

            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('schedule_id')->nullable();

            $table->string("reeschedule_by")->nullable();
            $table->string("cancel_by")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
