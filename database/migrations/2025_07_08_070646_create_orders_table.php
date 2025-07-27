<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // FK to custom_orders (nullable for plain re-orders)
            $table->unsignedBigInteger('custom_order_id')->nullable();
            $table->foreign('custom_order_id')
                  ->references('id')->on('custom_orders')
                  ->onDelete('set null');

            $table->string('order_number')->unique();

            // FK to school_agents
            $table->unsignedBigInteger('agent_id');
            $table->foreign('agent_id')
                  ->references('id')->on('school_agents')
                  ->onDelete('cascade');

            $table->enum('status', [
                'submitted',        // just placed by agent
                'in_design',
                'waiting_production',
                'in_production',
                'completed',
                'cancelled'
            ])->default('submitted');

            $table->json('customization_details')->nullable();
            $table->date('expected_production_date')->nullable();
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();   // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
