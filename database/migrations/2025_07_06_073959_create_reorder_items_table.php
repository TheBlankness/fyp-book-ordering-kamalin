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
        Schema::create('reorder_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reorder_id');
            $table->unsignedBigInteger('book_id');
            $table->string('title')->nullable();
            $table->string('cover')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('price', 8, 2)->default(0.00);
            $table->timestamps();

            // Foreign keys
            $table->foreign('reorder_id')->references('id')->on('reorders')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reorder_items');
    }
};
