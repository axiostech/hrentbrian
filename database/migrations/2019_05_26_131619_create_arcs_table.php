<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arcs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->default('01')->nullable();
            $table->integer('status')->default(1);
            $table->string('ref_number');
            $table->decimal('amount', 15, 2);
            $table->string('name');
            $table->string('email');

            $table->string('id_value')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('mandate_status')->nullable();
            $table->string('enrp_status')->nullable();
            $table->datetime('enrp_status_updated_at')->nullable();
            $table->string('fpx_transaction_id')->nullable();
            $table->string('ex_order_no')->nullable();

            $table->integer('tenancy_id')->unsinged();
            $table->integer('idtype_id')->unsinged()->nullable();
            $table->integer('profile_id')->unsigned()->nullable();
            $table->integer('creator_id')->unsigned()->nullable();
            $table->integer('updater_id')->unsigned()->nullable();
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
        Schema::dropIfExists('arcs');
    }
}
