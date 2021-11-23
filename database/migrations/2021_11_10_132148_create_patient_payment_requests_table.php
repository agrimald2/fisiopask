<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientPaymentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_payment_requests', function (Blueprint $table) {
            $table->id();
            $table->string('payment_method')->nullable();
            $table->string('reference')->nullable();

            $table->float('amount');
            $table->boolean('is_completed')->default(false);

            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('patient_payment_id')->nullable();

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
        Schema::dropIfExists('patient_payment_requests');
    }
}
