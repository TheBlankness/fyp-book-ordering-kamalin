<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('reorder_id')->nullable()->after('custom_order_id');
            $table->foreign('reorder_id')->references('id')->on('reorders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['reorder_id']);
            $table->dropColumn('reorder_id');
        });
    }

};
