<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraGamerToMedicalHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medical_histories', function (Blueprint $table) {
            //@Relationships:
            $table->unsignedBigInteger("t1")->nullable();
            $table->unsignedBigInteger("t2")->nullable();
            $table->unsignedBigInteger("t3")->nullable();
            
            //@Relationships:
            $table->unsignedBigInteger("a1")->nullable();
            $table->unsignedBigInteger("a2")->nullable();
            $table->unsignedBigInteger("a3")->nullable();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medical_histories', function (Blueprint $table) {
            //
        });
    }
}
