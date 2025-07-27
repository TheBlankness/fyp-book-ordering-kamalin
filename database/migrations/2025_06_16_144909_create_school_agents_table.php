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
        Schema::create('school_agents', function (Blueprint $table) {
            $table->id();

            // Company Details
            $table->string('company_name');
            $table->string('ssm_number')->unique();
            $table->string('company_reg_old')->nullable();
            $table->string('tin_number')->nullable();
            $table->string('sst_number')->nullable();
            $table->string('msic_code')->nullable();
            $table->string('business_activity')->nullable();
            $table->string('business_type')->nullable();
            $table->date('incorporation_date')->nullable();
            $table->string('company_email')->unique();
            $table->string('company_phone');
            $table->text('business_address');
            $table->string('website')->nullable();

            // e-Invoice Implementation Phase
            $table->string('einvoice_phase')->nullable();

            // Contact Person Info
            $table->string('full_name');
            $table->string('ic_number');
            $table->string('designation');
            $table->string('personal_email')->unique();
            $table->string('phone_number');

            // Tax & Bank Info
            $table->string('lhdn_tax_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_holder')->nullable();
            $table->string('einvoice_registered');
            $table->string('einvoice_email')->nullable();

            // Login credentials
            $table->string('username')->unique();
            $table->string('password');

            // Status
            $table->string('registration_status')->default('Pending');
            $table->timestamp('registration_date')->nullable();

            // File uploads
            $table->string('ssm_certificate');
            $table->string('company_logo')->nullable();
            $table->string('bank_proof')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_agents');
    }
};
