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

            $table->unsignedInteger('patient_id');
            $table->unsignedInteger('appointment_id')->nullable();

            $table->float('price');
            $table->float('payed')->default(0);

            $table->boolean('is_product');
            $table->unsignedInteger('qty')->default(1);

            $table->unsignedInteger('sessions_total')->default(1);
            $table->unsignedInteger('sessions_left')->default(1);

            $table->unsignedInteger('state');

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
