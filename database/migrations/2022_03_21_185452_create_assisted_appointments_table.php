<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssistedAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assisted_appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("appointment_id");
            $table->unsignedBigInteger("patient_rate_id")->nullable();
            
            $table->string("rate_charged")->nullable();
            $table->string("consumed")->nullable();
             
            $table->string("marked_by")->nullable();
            
            $table->softDeletes();
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
        Schema::dropIfExists('assisted_appointments');
    }
}
