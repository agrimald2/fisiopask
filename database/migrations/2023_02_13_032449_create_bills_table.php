<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('receiver');
            $table->string('paymentway');
            $table->string('payer')->nullable();
            $table->string('moneyOrigin');
            $table->string('billssubfamily_id');
            $table->string('description')->nullable();
            $table->string('quantity');


            $table->integer('status');
            $table->boolean('isDoubleChecked');

            $table->string('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->string('isApproved')->nullable();


            $table->string('second_approved_by')->nullable();
            $table->dateTime('second_approved_at')->nullable();
            $table->string('secondIsApproved')->nullable();


            $table->string('created_by');
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
        Schema::dropIfExists('bills');
    }
}
