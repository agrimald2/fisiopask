<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalRevisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_revisions', function (Blueprint $table) {
            $table->id();

            //@Patient - Doctor Relationships:
            $table->unsignedBigInteger("patient_id");
            $table->unsignedBigInteger("doctor_id");
            $table->unsignedBigInteger("history_group_id");

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
        Schema::dropIfExists('medical_revisions');
    }
}
