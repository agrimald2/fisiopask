<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_payments', function (Blueprint $table) {
            $table->id();

            $table->string('payment_method');
            $table->float('ammount');
            $table->string('concept')->nullable();

            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->unsignedBigInteger('patient_rate_id')->nullable();
            $table->unsignedBigInteger('patient_id');

            $table->string('payment_author')->nullable();
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
        Schema::dropIfExists('patient_payments');
    }
}
