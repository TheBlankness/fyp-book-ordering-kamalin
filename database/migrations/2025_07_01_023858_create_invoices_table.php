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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custom_order_id');
            $table->string('invoice_number')->unique();
            $table->date('issue_date')->nullable();
            $table->decimal('total_amount', 8, 2);
            $table->string('status')->default('unpaid'); // unpaid / paid
            $table->timestamps();

            $table->foreign('custom_order_id')->references('id')->on('custom_orders')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }

};
