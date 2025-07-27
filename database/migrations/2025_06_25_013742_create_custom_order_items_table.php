<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custom_order_id');
            $table->string('title');
            $table->string('cover');
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('custom_order_id')->references('id')->on('custom_orders')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_order_items');
    }
};
