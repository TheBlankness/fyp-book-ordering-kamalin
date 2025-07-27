<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('custom_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('issued_by')->nullable()->after('agent_id');

            // If your sales support users are in the `users` table:
            $table->foreign('issued_by')->references('id')->on('users')->onDelete('set null');

            // OR if your support users are in a separate table:
            // $table->foreign('issued_by')->references('id')->on('supports')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_orders', function (Blueprint $table) {
            $table->dropForeign(['issued_by']);
            $table->dropColumn('issued_by');
        });
    }
};
