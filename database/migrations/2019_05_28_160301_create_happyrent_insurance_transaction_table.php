<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHappyrentInsuranceTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('happyrent_insurance_transaction', function (Blueprint $table) {
            $table->bigIncrements('insurance_transaction_id');
            $table->string('insurance_billplz_id');
            $table->string('insurance_plan_id');
            $table->string('insurance_subtotal_amount');
            $table->string('insurance_taxable_amount')->default('inprogress');
            $table->string('insurance_total_amount');
            $table->string('insurance_transaction_status')->default('inprogress');
            $table->string('insurance_payment_by');
            $table->datetime('insurance_transaction_date');
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
        Schema::dropIfExists('happyrent_insurance_transaction');
    }
}
