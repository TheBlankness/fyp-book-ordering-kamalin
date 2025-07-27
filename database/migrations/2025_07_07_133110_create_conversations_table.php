<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationsTable extends Migration
{
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('designer_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->timestamps();

            $table->foreign('agent_id')->references('id')->on('school_agents')->onDelete('cascade');
            $table->foreign('designer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('custom_orders')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('conversations');
    }
}
