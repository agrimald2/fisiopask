<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("history_type_id");
            $table->unsignedInteger("input_type");
            $table->unsignedBigInteger("related_model")->nullable();
            $table->string("input_name");

            //We'll see if this ends up being useful...
            $table->unsignedInteger("index")->nullable();

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
        Schema::dropIfExists('history_attributes');
    }
}
