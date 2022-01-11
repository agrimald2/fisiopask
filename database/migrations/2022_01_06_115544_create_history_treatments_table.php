<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_treatments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("treatment_id");
            $table->unsignedBigInteger("medical_history_id")->nullable();
            $table->unsignedBigInteger("medical_revision_id")->nullable();
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
        Schema::dropIfExists('history_treatments');
    }
}
