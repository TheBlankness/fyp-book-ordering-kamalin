<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class SchoolAgent extends Authenticatable
{
    use Notifiable;

    protected $guard = 'agent';

    protected $fillable = [
        'company_name', 'ssm_number', 'company_reg_old', 'tin_number', 'sst_number',
        'msic_code', 'business_activity', 'business_type', 'incorporation_date',
        'email', 'company_phone', 'business_address', 'website',
        'einvoice_phase', 'full_name', 'ic_number', 'designation', 'personal_email',
        'phone_number', 'lhdn_tax_number', 'bank_name', 'bank_account_number',
        'bank_account_holder', 'einvoice_registered', 'einvoice_email',
        'username', 'password', 'registration_status', 'registration_date',
        'ssm_certificate', 'company_logo', 'bank_proof',
    ];

    protected $hidden = ['password'];

    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    public function getAuthIdentifierName()
    {
        return 'email';
    }
}
