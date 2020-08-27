<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCStoreProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_store_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug');
            $table->text('keywords')->nullable();
            $table->longText('description')->nullable();
            $table->string('dimension')->nullable();
            $table->string('weight')->nullable();
            $table->text('delivery_description')->nullable();
            $table->text('warning_info')->nullable();
            $table->unsignedBigInteger('price')->default(0);
            $table->unsignedBigInteger('count')->default(0);
            $table->text('allowed_province')->nullable();
            $table->text('allowed_cities')->nullable();
            $table->int('delivery_delay')->default(0); // day of delay
            $table->string('delivery_delay_type')->nullable(); // working day or holiday etc
            $table->boolean('active')->default(0);
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
        Schema::dropIfExists('c_store_products');
    }
}
