<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCharityPaymentPTSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charity_payment_p_t_s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pattern_id');
            $table->unsignedBigInteger('title_id');
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
        Schema::dropIfExists('charity_payment_p_t_s');
    }
}
