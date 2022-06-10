<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryHasTreatmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_has_treatment', function (Blueprint $table) {
            $table->unsignedBigInteger("treatment_id");
            $table->unsignedBigInteger("history_id");
            $table->boolean("isRevision");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_has_treatment');
    }
}
