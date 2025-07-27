<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reorders', function (Blueprint $table) {
            $table->decimal('delivery_fee', 10, 2)->nullable()->after('delivery_address');
        });
    }


    public function down()
    {
        Schema::table('reorders', function (Blueprint $table) {
            $table->dropColumn('delivery_fee');
        });
    }

};
