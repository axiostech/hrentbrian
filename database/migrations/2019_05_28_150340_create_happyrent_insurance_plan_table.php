<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHappyrentInsurancePlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('happyrent_insurance_plan', function (Blueprint $table) {
            $table->bigIncrements('insurance_plan_id');
            $table->string('insurance_plan_name');
            $table->string('insurance_plan_price');
            $table->string('insurance_plan_duration');
            $table->integer('insurance_plan_status')->default(1);
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
        Schema::dropIfExists('happyrent_insurance_plan');
    }
}
