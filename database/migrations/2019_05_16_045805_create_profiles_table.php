<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('roc')->nullable();
            $table->string('attn_name')->nullable();
            $table->string('attn_phone_number')->nullable();
            $table->string('address')->nullable();
            $table->string('postcode')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('domain_name')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('theme_color')->nullable();
            $table->boolean('is_superprofile')->default(0);
            $table->string('tenancy_running_num')->nullable();
            $table->string('prefix')->nullable();
            $table->string('email');
            $table->boolean('status')->default(1);

            $table->integer('user_id')->unsigned()->nullable();
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
        Schema::dropIfExists('profiles');
    }
}
