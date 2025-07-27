<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reorders', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('original_custom_order_id'); // link to original custom order
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('school_id')->nullable();

            $table->string('design_file')->nullable();
            $table->string('delivery_option');
            $table->string('delivery_address')->nullable();
            $table->text('notes')->nullable();

            $table->string('status')->default('submitted');
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('original_custom_order_id')->references('id')->on('custom_orders')->onDelete('cascade');
            $table->foreign('agent_id')->references('id')->on('school_agents')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reorders');
    }
};

