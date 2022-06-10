<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryHasAnalisisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_has_analisis', function (Blueprint $table) {
            $table->unsignedBigInteger("analisis_id");
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
        Schema::dropIfExists('history_has_analisis');
    }
}
