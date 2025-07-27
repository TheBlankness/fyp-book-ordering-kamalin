<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->after('id');
            $table->string('order_type')->nullable()->after('order_id'); // e.g. 'custom', 'reorder'
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('order_id');
            $table->dropColumn('order_type');
        });
    }

};
