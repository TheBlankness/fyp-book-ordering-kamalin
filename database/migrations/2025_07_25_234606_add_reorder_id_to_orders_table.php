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
    Schema::table('orders', function (Blueprint $table) {
        $table->unsignedBigInteger('reorder_id')->nullable()->after('custom_order_id');

        // Optional foreign key constraint
        $table->foreign('reorder_id')->references('id')->on('reorders')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropForeign(['reorder_id']);
        $table->dropColumn('reorder_id');
    });
}

};
