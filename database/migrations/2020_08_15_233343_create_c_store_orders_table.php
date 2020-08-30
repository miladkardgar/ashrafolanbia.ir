<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCStoreOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_store_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('province');
            $table->unsignedInteger('cities');
            $table->string('receiver');
            $table->string('zip_code');
            $table->string('phone');
            $table->string('mobile');
            $table->text('description');
            $table->string('condolences_to');
            $table->string('from_as');
            $table->string('late_name');
            $table->date('date');
            $table->time('time');
            $table->text('meeting_address');
            $table->string('lat');
            $table->string('lon');
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
        Schema::dropIfExists('c_store_orders');
    }
}
