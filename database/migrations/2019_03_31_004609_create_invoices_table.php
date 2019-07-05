<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_number')->nullable();
            $table->datetime('send_date')->nullable();
            $table->datetime('due_date')->nullable();
            $table->datetime('paid_date')->nullable();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('grandtotal', 15, 2);
            $table->integer('status')->default(1);
            $table->integer('payment_status')->default(1);
            $table->decimal('paid_amount', 15, 2)->nullable();
            $table->boolean('is_sent')->default(0);

            $table->integer('tenancy_id')->unsigned()->nullable();
            $table->integer('unit_id')->unsigned()->nullable();
            $table->integer('profile_id')->unsigned()->nullable();
            $table->integer('creator_id')->unsigned()->nullable();
            $table->integer('updater_id')->unsigned()->nullable();
            $table->integer('tax_id')->unsigned()->nullable();
            $table->integer('transaction_id')->unsigned()->nullable();
            $table->integer('recurrence_id')->unsigned()->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
