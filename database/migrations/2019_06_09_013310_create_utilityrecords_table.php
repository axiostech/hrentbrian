<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUtilityrecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('utilityrecords', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('month');
            $table->integer('year');
            $table->string('reading')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('image_url')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('status')->default(1);
            $table->string('other_field')->nullable();
            $table->integer('type')->nullable();
            $table->boolean('is_request_cancel')->default(0);

            $table->integer('tenancy_id')->unsinged();
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
        Schema::dropIfExists('utilityrecords');
    }
}
