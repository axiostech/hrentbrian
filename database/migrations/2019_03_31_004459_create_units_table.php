<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('block_number')->nullable();
            $table->string('unit_number')->nullable();
            $table->text('address')->nullable();
            $table->string('label')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('squarefeet')->nullable();
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->string('bedbath_room')->nullable();
            $table->datetime('purchased_at')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->integer('property_id')->unsigned();
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
        Schema::dropIfExists('units');
    }
}
