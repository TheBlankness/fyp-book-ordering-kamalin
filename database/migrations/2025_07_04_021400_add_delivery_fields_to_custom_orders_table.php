<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('custom_orders', function (Blueprint $table) {
            $table->string('delivery_option')->nullable()->after('notes');
            $table->text('delivery_address')->nullable()->after('delivery_option');
            $table->decimal('delivery_fee', 8, 2)->nullable()->after('delivery_address');
        });
    }

    public function down()
    {
        Schema::table('custom_orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_option', 'delivery_address', 'delivery_fee']);
        });
    }

};
