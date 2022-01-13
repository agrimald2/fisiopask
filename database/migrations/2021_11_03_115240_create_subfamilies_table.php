<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubfamiliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subfamilies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('family_id');
            $table->timestamps();
        });

        Schema::create('doctor_subfamily', function (Blueprint $table) {
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('subfamily_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subfamilies');
        Schema::dropIfExists('doctor_subfamily');
    }
}
