<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_histories', function (Blueprint $table) {
            $table->id();

            //@Patient - Doctor Relationships:
            $table->unsignedBigInteger("patient_id");
            $table->unsignedBigInteger("doctor_id");

            //@ActualDisease:
            $table->string("motive")->nullable();
            $table->string("symptoms")->nullable();

            //@Biological Functions:
            $table->string("appetite")->nullable();
            $table->string("thirst")->nullable();
            $table->string("sleep")->nullable(); 
            $table->string("mood")->nullable();
            $table->string("weight_loss")->nullable();
            $table->string("diuresis")->nullable();     
            $table->string("depositions")->nullable();       

            //@Background:
            $table->string("diseases")->nullable();
            $table->string("meds")->nullable();
            $table->string("alergies")->nullable();

            //@Phisical Test:
                //@Vitals:
                    $table->string("vital1")->nullable();
                    $table->string("vital2")->nullable();
                    $table->string("vital3")->nullable();   
                    $table->string("vital4")->nullable();

                    $table->string("weight")->nullable();
                    $table->string("height")->nullable();   
                    $table->string("imc")->nullable(); 
                //@Others:
                    $table->string("general_test")->nullable();
                    $table->string("head_neck")->nullable();
                    $table->string("respiratory")->nullable();   
                    $table->string("cardiovascular")->nullable();
                    $table->string("abs")->nullable();
                    $table->string("genitourinario")->nullable();
                    $table->string("nervous_system")->nullable();   
                    $table->string("extremities")->nullable(); 
                    $table->string("preferencial_test")->nullable(); 


            //@Info:
            $table->string("background")->nullable();
            $table->string("warnings")->nullable();
            $table->string("description")->nullable();

            //@Scales:
            $table->unsignedBigInteger("pain_scale");
            $table->unsignedBigInteger("force_scale");
            $table->unsignedBigInteger("joint_range");
            $table->unsignedBigInteger("recovery_progress");
            
            //@Relationships:
            $table->unsignedBigInteger("diagnostic_id");
            $table->unsignedBigInteger("treatment_id");
            $table->unsignedBigInteger("analysis_id");
            $table->unsignedBigInteger("affected_area_id");

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
        Schema::dropIfExists('medical_histories');
    }
}
