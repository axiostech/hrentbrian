<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHappyrentSiteSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('happyrent_site_setting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('billplz_secret_key');
            $table->string('billplz_signature');
            $table->string('billplz_collection_id');
            $table->string('tax_amount');
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
        Schema::dropIfExists('happyrent_site_setting');
    }
}
