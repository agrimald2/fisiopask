<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('lastname1');
            $table->string('lastname2');

            $table->string('email')->nullable();

            $table->string('dni');
            $table->string('birth_date');
            $table->string('sex');
            $table->string('phone')->nullable();

            $table->string('district')->nullable();
            $table->integer('reccomendation_id')->nullable();

            $table->string('token')->nullable();

            //@note: New Values for Anandamida Clinical Histories
            $table->string('status')->nullable();
            $table->string('address')->nullable();
            $table->string('insurance')->nullable();
            $table->string('ocupation')->nullable();
            $table->string('religion')->nullable();
            $table->string('birth_place')->nullable();


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
        Schema::dropIfExists('patients');
    }
}
