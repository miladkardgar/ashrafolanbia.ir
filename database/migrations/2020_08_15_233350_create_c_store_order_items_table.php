<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCStoreOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_store_order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('CSO_id');
            $table->unsignedInteger('CSP_id');
            $table->string('name');
            $table->integer('quantity');
            $table->unsignedInteger('price');
            $table->text('slug');
            $table->text('image');
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
        Schema::dropIfExists('c_store_order_items');
    }
}
