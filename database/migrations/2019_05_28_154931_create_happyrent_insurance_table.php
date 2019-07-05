<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHappyrentInsuranceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('happyrent_insurance', function (Blueprint $table) {
            $table->bigIncrements('insurance_id');
            $table->integer('insurance_unit_id');
            $table->text('insurance_plan_id');
            $table->text('insurance_transaction_id');
            $table->datetime('insurance_date')->nullable();
            $table->integer('insurance_status')->default(0);;
            $table->text('insurance_doc_path')->nullable();
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
        Schema::dropIfExists('happyrent_insurance');
    }
}
