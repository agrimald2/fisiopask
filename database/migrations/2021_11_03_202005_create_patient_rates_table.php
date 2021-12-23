<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_rates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('price');
            $table->unsignedInteger('qty')->default(1);

            $table->unsignedInteger('amount_paid');
            $table->unsignedInteger('sessions_left');

            $table->unsignedBigInteger('patient_id');

            $table->unsignedBigInteger('rate_id')->nullable();
            $table->unsignedBigInteger('appointment_id')->nullable();

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
        Schema::dropIfExists('patient_rates');
    }
}
