<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenancies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('tenancy_date')->nullable();
            $table->datetime('datefrom');
            $table->datetime('dateto');
            $table->integer('duration_month');
            $table->decimal('rental', 15, 2);
            $table->decimal('deposit', 15, 2);
            $table->text('remarks')->nullable();
            $table->boolean('is_arc')->default(0);
            $table->integer('status')->default(1);
            $table->string('code')->nullable();
            $table->string('room_name')->nullable();
            $table->integer('due_days')->nullable();
            $table->string('agreement_url')->nullable();

            $table->integer('unit_id')->unsigned();
            $table->integer('tenant_id')->unsigned();
            $table->integer('beneficiary_id')->unsigned()->nullable();
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
        Schema::dropIfExists('tenancies');
    }
}
