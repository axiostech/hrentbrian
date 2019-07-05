<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecurrencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurrences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('total_term');
            $table->integer('current_term');
            $table->decimal('paid_amount', 15, 2);
            $table->decimal('total_amount', 15, 2);
            $table->datetime('paid_date')->nullable();
            $table->integer('month_send_date')->default(1);
            $table->integer('month_due_day')->default(7);
            $table->integer('status')->default(1);
            $table->integer('month')->nullable();
            $table->integer('year')->nullable();
            $table->boolean('is_auto')->default(0);

            $table->integer('arc_id')->nullable();
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
        Schema::dropIfExists('recurrences');
    }
}
