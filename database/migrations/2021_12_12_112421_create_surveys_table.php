<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('office_score')->nullable();
            $table->unsignedBigInteger('service_score')->nullable();
            $table->unsignedBigInteger('doctor_score')->nullable();
            $table->string('comment');

            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->date('survey_date');

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
        Schema::dropIfExists('surveys');
    }
}
