<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reorders', function (Blueprint $table) {
            $table->date('production_date')->nullable();
            $table->text('planner_notes')->nullable();
        });
    }

    public function down()
    {
        Schema::table('reorders', function (Blueprint $table) {
            $table->dropColumn('production_date');
            $table->dropColumn('planner_notes');
        });
    }


};
